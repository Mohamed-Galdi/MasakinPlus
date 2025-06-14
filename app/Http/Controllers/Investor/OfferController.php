<?php

namespace App\Http\Controllers\Investor;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'typeFilter' => 'nullable|string|exists:property_types,id',
            'cityFilter' => 'nullable|array',
            'cityFilter.*' => 'string',
            'minInvestAmount' => 'nullable|numeric|min:0',
            'maxInvestAmount' => 'nullable|numeric|min:0',
            'minMonthlyRevenue' => 'nullable|numeric|min:0',
            'maxMonthlyRevenue' => 'nullable|numeric|min:0',
            'fundedPercentageFilter' => 'nullable|numeric|min:0|max:100',
        ]);

        $typeOptions = Cache::remember('property_type_options', now()->addDays(7), fn() => PropertyType::options());

        $baseQuery = Property::with('images', 'amenities', 'investments')
            ->where('status', PropertyStatus::OpenForInvestment);

        $mapProperties = $baseQuery->get();

        $properties = $baseQuery->when($filters['typeFilter'] ?? null, fn($query, $typeFilter) =>
        $query->where('type', $typeFilter))
            ->when(!empty($filters['cityFilter']) ?? null, fn($query) =>
            $query->whereIn('city', $filters['cityFilter']))
            ->when($filters['minInvestAmount'] ?? null, fn($query, $minInvestAmount) =>
            $query->where('investment_required', '>=', $minInvestAmount))
            ->when($filters['maxInvestAmount'] ?? null, fn($query, $maxInvestAmount) =>
            $query->where('investment_required', '<=', $maxInvestAmount))
            ->when($filters['minMonthlyRevenue'] ?? null, fn($query, $minMonthlyRevenue) =>
            $query->where('monthly_rent', '>=', $minMonthlyRevenue))
            ->when($filters['maxMonthlyRevenue'] ?? null, fn($query, $maxMonthlyRevenue) =>
            $query->where('monthly_rent', '<=', $maxMonthlyRevenue))
            ->when($filters['fundedPercentageFilter'] ?? null, fn($query, $fundedPercentageFilter) =>
            $query->where('investor_share', '>=', $fundedPercentageFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        return inertia('Investor/Offers/index', [
            'properties' => $properties,
            'mapProperties' => $mapProperties,
            'typeOptions' => $typeOptions,
            'cityFilter' => $filters['cityFilter'] ?? [],
            'typeFilter' => $filters['typeFilter'] ?? '',
            'minInvestAmount' => $filters['minInvestAmount'] ?? null,
            'maxInvestAmount' => $filters['maxInvestAmount'] ?? null,
            'minMonthlyRevenue' => $filters['minMonthlyRevenue'] ?? null,
            'maxMonthlyRevenue' => $filters['maxMonthlyRevenue'] ?? null,
            'fundedPercentageFilter' => $filters['fundedPercentageFilter'] ?? null,
        ]);
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);

        $property->load('images', 'amenities');

        return inertia('Investor/Offers/show', compact('property'));
    }
}
