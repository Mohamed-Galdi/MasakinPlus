<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentOffer;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestmentRequestController extends Controller
{
    public function index(Request $request)
    {
        $investmentRequests = InvestmentRequest::with('property.owner', 'property.images')
            ->when($request->statusFilter, function ($query) use ($request) {
                $query->where('status', $request->statusFilter);
            })
            ->when($request->search, function ($query) use ($request) {
                // Owner filter
                $query->whereHas('property.owner', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $statusFilter = $request->get('statusFilter') ?? '';
        $search = $request->get('search') ?? '';

        $investmentRequestsStatusOptions = InvestmentRequestStatus::options();

        return inertia('Admin/InvestmentRequests/index', compact('investmentRequests', 'investmentRequestsStatusOptions', 'statusFilter', 'search'));
    }

    public function show(InvestmentRequest $investmentRequest)
    {
        $investmentRequest->load('property.images', 'property.owner');

        $investmentOffer = $investmentRequest->property->investmentOffer;

        $investmentRequestsStatusOptions = InvestmentRequestStatus::options();

        $approved = InvestmentRequestStatus::Approved->value;

        $props = [
            'investmentRequest' => $investmentRequest,
            'investmentRequestsStatusOptions' => $investmentRequestsStatusOptions,
            'approved' => $approved,
        ];

        if ($investmentOffer) {
            $props['investmentOffer'] = $investmentOffer;
        }

        return inertia('Admin/InvestmentRequests/view', $props);
    }

    public function createOffer(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'suggested_valuation' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_investment_amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_monthly_operating_cost' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_nightly_rent' => 'required|numeric|min:0|max:10000|regex:/^\d+(\.\d{1,2})?$/',
            'owner_share' => 'required|numeric|min:0|max:100',
            'investor_share' => 'required|numeric|min:0|max:100',
            'platform_fee_share' => 'required|numeric|min:0|max:100',
            'admin_note' => 'nullable|string|max:1500',
        ], [
            'regex' => 'يجب أن يحتوي الحقل :attribute على رقم بما يصل إلى رقمين عشريين.',
        ]);

        // Validate that shares sum to 100%
        $totalShares = $request->owner_share + $request->investor_share + $request->platform_fee_share;
        if (abs($totalShares - 100) > 0.01) { // Allow small float errors
            return back()->withErrors(['owner_share' => 'مجموع الحصص يجب أن يكون 100%']);
        }


        if (InvestmentOffer::where('property_id', $request->property_id)->exists()) {
            return back()->withErrors(['property_id' => 'هذا العقار لديه عرض استثمار موجود بالفعل']);
        }

        try {
            DB::beginTransaction();

            $investmentOffer = new InvestmentOffer();
            $investmentOffer->property_id = $request->property_id;
            $investmentOffer->suggested_valuation = $request->suggested_valuation;
            $investmentOffer->suggested_investment_amount = $request->suggested_investment_amount;
            $investmentOffer->suggested_monthly_operating_cost = $request->suggested_monthly_operating_cost;
            $investmentOffer->suggested_nightly_rent = $request->suggested_nightly_rent;
            $investmentOffer->owner_share = $request->owner_share / 100;
            $investmentOffer->investor_share = $request->investor_share / 100;
            $investmentOffer->platform_fee_share = $request->platform_fee_share / 100;
            $investmentOffer->admin_note = $request->admin_note;
            $investmentOffer->save();

            // Update related InvestmentRequest status if needed
            InvestmentRequest::where('property_id', $request->property_id)->update(['status' => InvestmentRequestStatus::OfferSent->value]);
            Property::where('id', $request->property_id)->update(['status' => PropertyStatus::InvestmentOfferSent->value]);

            DB::commit();

            return redirect()->back()->with('success', 'تم إنشاء عرض الاستثمار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء العرض']);
        }
    }

    public function updateOffer(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:investment_offers,id',
            'suggested_valuation' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_investment_amount' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_monthly_operating_cost' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'suggested_nightly_rent' => 'required|numeric|min:0|max:10000|regex:/^\d+(\.\d{1,2})?$/',
            'owner_share' => 'required|numeric|min:0|max:100',
            'investor_share' => 'required|numeric|min:0|max:100',
            'platform_fee_share' => 'required|numeric|min:0|max:100',
            'admin_note' => 'nullable|string|max:1500',
        ], [
            'regex' => 'يجب أن يحتوي الحقل :attribute على رقم بما يصل إلى رقمين عشريين.',
        ]);

        // Validate that shares sum to 100%
        $totalShares = $request->owner_share + $request->investor_share + $request->platform_fee_share;
        if (abs($totalShares - 100) > 0.01) { // Allow small float errors
            return back()->withErrors(['owner_share' => 'مجموع الحصص يجب أن يكون 100%']);
        }

        $investmentOffer = InvestmentOffer::findOrFail($request->id);
        $investmentOffer->suggested_valuation = $request->suggested_valuation;
        $investmentOffer->suggested_investment_amount = $request->suggested_investment_amount;
        $investmentOffer->suggested_monthly_operating_cost = $request->suggested_monthly_operating_cost;
        $investmentOffer->suggested_nightly_rent = $request->suggested_nightly_rent;
        $investmentOffer->owner_share = $request->owner_share / 100;
        $investmentOffer->investor_share = $request->investor_share / 100;
        $investmentOffer->platform_fee_share = $request->platform_fee_share / 100;
        $investmentOffer->admin_note = $request->admin_note;        
        $investmentOffer->save();

        return redirect()->back()->with('success', 'تم تحديث العرض بنجاح');
    }
}
