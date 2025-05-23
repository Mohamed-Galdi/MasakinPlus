<?php

namespace App\Http\Controllers\Owner;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentOffer;
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

        $properties = Property::where('owner_id', $user->id)->where('status', PropertyStatus::Draft->value)->whereDoesntHave('investmentRequest')->get();

        $investmentRequestsStatusOptions = InvestmentRequestStatus::options();

        return inertia('Owner/InvestmentRequests/index', compact('properties', 'investmentRequests', 'investmentRequestsStatusOptions'));
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

    }

    public function show($id)
    {
        $investmentRequest = InvestmentRequest::findOrFail($id);

        // authorization
        Gate::authorize('view', $investmentRequest);

        $investmentRequest->load('property.images');

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


        return inertia('Owner/InvestmentRequests/view', $props);
    }

    public function acceptOffer($id)
    {
        $investmentOffer = InvestmentOffer::findOrFail($id);

        Gate::authorize('acceptOffer', $investmentOffer);

        try {
            DB::beginTransaction();

            $property = Property::findOrFail($investmentOffer->property_id);
            $property->valuation = $investmentOffer->suggested_valuation;
            $property->investment_required = $investmentOffer->suggested_investment_amount;
            $property->monthly_operating_cost = $investmentOffer->suggested_monthly_operating_cost;
            $property->nightly_rent = $investmentOffer->suggested_nightly_rent;
            $property->owner_share = $investmentOffer->owner_share;
            $property->investor_share = $investmentOffer->investor_share;
            $property->platform_fee_share = $investmentOffer->platform_fee_share;
            $property->status = PropertyStatus::OpenForInvestment->value;
            $property->save();

            $investmentRequest = InvestmentRequest::where('property_id', $investmentOffer->property_id)->first();

            // Add null check for investment request
            if ($investmentRequest) {
                $investmentRequest->status = InvestmentRequestStatus::Approved->value;
                $investmentRequest->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'تم قبول العرض بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء قبول العرض ']);
        }
    }

    public function rejectOffer( $id)
    {
        $investmentOffer = InvestmentOffer::findOrFail($id);

        Gate::authorize('rejectOffer', $investmentOffer);

        $investmentRequest = InvestmentRequest::where('property_id', $investmentOffer->property_id)->first();
        $property = Property::findOrFail($investmentOffer->property_id);


        try {
            DB::beginTransaction();
            $investmentOffer->delete();
            $investmentRequest->delete();
            $property->status = PropertyStatus::Draft->value;
            $property->save();

            DB::commit();
            return redirect()->route('owner.investment-requests.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء رفض العرض ']);
        }

        return back();
    }
}
