<?php

namespace App\Http\Controllers\Investor;

use App\Enums\InvestStatus;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Property;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all investments for the authenticated user with property details
        $investments = Investment::with([
            'property' => function ($query) {
                $query->select([
                    'id',
                    'title',
                    'city',
                    'type',
                    'status',
                    'investment_required',
                    'nightly_rent',
                    'expected_occupancy',
                    'owner_share',
                    'investor_share',
                    'platform_fee_share'
                ]);
            },
            'property.images' => function ($query) {
                $query->select('id', 'property_id', 'path')
                    ->orderBy('order', 'asc')
                    ->limit(1); // Only get the first image
            }
        ])
            ->where('user_id', $user->id)
            ->where('status', InvestStatus::Active) // Only active investments
            ->orderBy('invested_at', 'desc')
            ->get();

        // Group investments by property
        $groupedInvestments = $investments->groupBy('property.id')->map(function ($propertyInvestments) {
            $property = $propertyInvestments->first()->property;

            // Calculate totals for all investments in this property
            $totalAmountInvested = $propertyInvestments->sum('amount');
            $totalFunded = $property->total_funded;
            $investmentPercentage = $totalFunded > 0 ? ($totalAmountInvested / $property->investment_required) * 100 : 0;
            $propertyPercentageFunded = $property->percentage_funded;

            // Calculate expected monthly revenue for all investments in this property
            $propertyMonthlyIncome = $property->expected_monthly_income;
            $investorTotalShare = $property->investors_monthly_share;
            $myExpectedRevenue = $property->investment_required > 0
                ? ($totalAmountInvested / $property->investment_required) * $investorTotalShare
                : 0;
            
            // Get the earliest investment date
            $firstInvestmentDate = $propertyInvestments->min('invested_at');

            // Count number of investments
            $investmentCount = $propertyInvestments->count();

            return [
                'property_id' => $property->id,
                'investment_count' => $investmentCount,
                'total_amount_invested' => $totalAmountInvested,
                'first_invested_at' => $firstInvestmentDate,
                'investment_percentage' => round($investmentPercentage, 2),
                'expected_monthly_revenue' => round($myExpectedRevenue, 2),
                'investments' => $propertyInvestments->map(function ($investment) {
                    return [
                        'id' => $investment->id,
                        'amount' => $investment->amount,
                        'invested_at' => $investment->invested_at,
                    ];
                }),
                'property' => [
                    'id' => $property->id,
                    'title' => $property->title,
                    'city' => $property->city,
                    'type' => $property->type,
                    'status' => $property->status,
                    'investment_required' => $property->investment_required,
                    'total_funded' => $totalFunded,
                    'percentage_funded' => round($propertyPercentageFunded, 2),
                    'expected_monthly_income' => $propertyMonthlyIncome,
                    'image' => $property->images->first()?->path,
                ]
            ];
        })->values(); // Reset array keys

        // Calculate summary metrics
        $totalInvested = $groupedInvestments->sum('total_amount_invested');
        $totalExpectedMonthlyRevenue = $groupedInvestments->sum('expected_monthly_revenue');
        $totalProperties = $groupedInvestments->count();

        // Calculate average return percentage
        $averageReturnPercentage = $totalInvested > 0 ? ($totalExpectedMonthlyRevenue / $totalInvested) * 100 : 0;

        return inertia('Investor/Investments/index', [
            'investments' => $groupedInvestments,
            'summary' => [
                'total_invested' => $totalInvested,
                'total_expected_monthly_revenue' => round($totalExpectedMonthlyRevenue, 2),
                'total_properties' => $totalProperties,
                'average_monthly_return_percentage' => round($averageReturnPercentage, 2),
            ]
        ]);
    }

    public function show($propertyId)
    {
        $user = Auth::user();

        // Get the property with all necessary relationships
        $property = Property::with([
            'images',
            'owner',
            'amenities',
            'investments' => function ($query) {
                $query->where('status', InvestStatus::Active)
                    ->with('user:id,name');
            }
        ])->findOrFail($propertyId);

        // Get user's investments in this property
        $userInvestments = Investment::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->where('status', InvestStatus::Active)
            ->orderBy('invested_at', 'desc')
            ->get();

        // Check if user has investments in this property
        if ($userInvestments->isEmpty()) {
            abort(404, 'لا توجد استثمارات لك في هذا العقار');
        }

        // Calculate user's total investment data
        $totalUserInvestment = $userInvestments->sum('amount');
        $totalFunded = $property->total_funded;
        $userInvestmentPercentage = $totalFunded > 0 ? ($totalUserInvestment / $property->investment_required) * 100 : 0;

        // Calculate expected monthly revenue for user
        $propertyMonthlyIncome = $property->expected_monthly_income;
        $investorTotalShare = $property->investors_monthly_share;
        $userExpectedRevenue = $property->investment_required > 0
            ? ($totalUserInvestment / $property->investment_required) * $investorTotalShare
            : 0;
        // Get other investors (excluding current user)
        $otherInvestors = $property->investments
            ->where('user_id', '!=', $user->id)
            ->groupBy('user_id')
            ->map(function ($investments) {
                $user = $investments->first()->user;
                $totalAmount = $investments->sum('amount');

                return [
                    'name' => $this->formatInvestorName($user->name),
                    'total_investment' => $totalAmount,
                    'investment_count' => $investments->count(),
                ];
            })
            ->values();

        return inertia('Investor/Investments/show', [
            'property' => $property,
            'userInvestments' => $userInvestments->map(function ($investment) {
                return [
                    'id' => $investment->id,
                    'amount' => $investment->amount,
                    'invested_at' => $investment->invested_at,
                ];
            }),
            'investmentSummary' => [
                'total_user_investment' => $totalUserInvestment,
                'user_investment_percentage' => round($userInvestmentPercentage, 2),
                'user_expected_monthly_revenue' => round($userExpectedRevenue, 2),
                'investment_count' => $userInvestments->count(),
            ],
            'otherInvestors' => $otherInvestors,
        ]);
    }

    /**
     * Format investor name to show first name + first letter of last name + dots
     */
    private function formatInvestorName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));

        if (count($nameParts) < 2) {
            return $fullName; // Return as is if only one name
        }

        $firstName = $nameParts[0];
        $lastNameFirstLetter = mb_substr($nameParts[count($nameParts) - 1], 0, 1);

        return $firstName . ' ' . $lastNameFirstLetter . '...';
    }

    public function invest(Request $request, InvestmentService $investmentService)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'property_id' => 'required|uuid|exists:properties,id',
        ]);

        $user = Auth::user();

        try {
            $investment = $investmentService->createInvestment(
                user: $user,
                propertyId: $validated['property_id'],
                amount: $validated['amount']
            );

            return redirect()->route('dashboard')->with('success', 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['amount' => $e->getMessage()]);
        }
    }

}
