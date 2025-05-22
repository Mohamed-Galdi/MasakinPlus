<?php

namespace App\Http\Controllers\Owner;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InvestmentRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $investmentRequests = $user->investmentRequests()->orderBy('updated_at', 'desc')->paginate(10);
        $investmentRequests->load('property.images');
        // $investmentRequests = [];

        $properties = Property::where('owner_id', $user->id)->where('status', PropertyStatus::Draft->value)->whereDoesntHave('investmentRequests')->get();

        $InvestmentRequestStatusOptions = InvestmentRequestStatus::options();

        return inertia('Owner/InvestmentRequests/index', compact('properties', 'investmentRequests', 'InvestmentRequestStatusOptions'));
    }

    public function create(Request $request)
    {
        // authorization
        $property = Property::findOrFail($request->property_id);
        Gate::authorize('create', [InvestmentRequest::class, $property]);

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'suggested_valuation' => 'required|numeric|min:0',
            'suggested_investment_amount' => 'required|numeric|min:0',
            'suggested_monthly_operating_cost' => 'required|numeric|min:0',
            'suggested_nightly_rent' => 'required|numeric|min:0|max:10000',
            'owner_note' => 'max:1500',
        ]);

        DB::transaction(function () use ($request, $property) {
            $investmentRequest = new InvestmentRequest;
            $investmentRequest->property_id = $request->property_id;
            $investmentRequest->suggested_valuation = $request->suggested_valuation;
            $investmentRequest->suggested_investment_amount = $request->suggested_investment_amount;
            $investmentRequest->suggested_monthly_operating_cost = $request->suggested_monthly_operating_cost;
            $investmentRequest->suggested_nightly_rent = $request->suggested_nightly_rent;
            $investmentRequest->owner_note = $request->owner_note;
            $investmentRequest->status = InvestmentRequestStatus::Pending;
            $investmentRequest->save();

            $property->status = PropertyStatus::InvestmentRequested;
            $property->save();
        });

        return back();
    }

    public function show($id)
    {
        $investmentRequest = InvestmentRequest::findOrFail($id);

        // authorization
        Gate::authorize('view', $investmentRequest);

        $investmentRequest->load('property.images');

        $InvestmentRequestStatusOptions = InvestmentRequestStatus::options();

        return inertia('Owner/InvestmentRequests/view', compact('investmentRequest', 'InvestmentRequestStatusOptions'));
    }

    public function update(Request $request)
    {
        $investmentRequest = InvestmentRequest::findOrFail($request->request_id);

        // authorization
        Gate::authorize('update', $investmentRequest );

        $request->validate([
            'request_id' => 'required',
            'suggested_valuation' => 'required|numeric|min:0',
            'suggested_investment_amount' => 'required|numeric|min:0',
            'suggested_monthly_operating_cost' => 'required|numeric|min:0',
            'suggested_nightly_rent' => 'required|numeric|min:0|max:10000',
            'owner_note' => 'max:1500',
        ]);

        $investmentRequest->suggested_valuation = $request->suggested_valuation;
        $investmentRequest->suggested_investment_amount = $request->suggested_investment_amount;
        $investmentRequest->suggested_monthly_operating_cost = $request->suggested_monthly_operating_cost;
        $investmentRequest->suggested_nightly_rent = $request->suggested_nightly_rent ?? 0;
        $investmentRequest->owner_note = $request->owner_note ?? '';
        $investmentRequest->status = InvestmentRequestStatus::Pending;
        $investmentRequest->save();

        $property = Property::findOrFail($investmentRequest->property_id);
        $property->status = PropertyStatus::InvestmentRequested;
        $property->save();

        return back();
    }
}
