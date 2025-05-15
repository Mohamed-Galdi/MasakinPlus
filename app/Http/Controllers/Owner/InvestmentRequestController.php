<?php

namespace App\Http\Controllers\Owner;

use App\Enums\InvestmentRequestStatus;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\Property;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $investmentRequests = $user->investmentRequests()->orderBy('updated_at', 'desc')->paginate(10);
        $investmentRequests->load('property.images');
        // $investmentRequests = [];

        $properties = Property::where('owner_id', $user->id)->where('status', 'draft')->whereDoesntHave('investmentRequests')->get();

        $InvestmentRequestStatusOptions = InvestmentRequestStatus::options();

        return inertia('Owner/InvestmentRequests/index', compact('properties', 'investmentRequests', 'InvestmentRequestStatusOptions'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'suggested_investment_amount' => 'required|numeric|min:0|max:1000000',
            'suggested_daily_rent_price' => 'required|numeric|min:0|max:10000',
            'owner_note' => 'max:1500',
        ]);


        $investmentRequest = new InvestmentRequest();
        $investmentRequest->property_id = $request->property_id;
        $investmentRequest->suggested_investment_amount = $request->suggested_investment_amount;
        $investmentRequest->suggested_daily_rent_price = $request->suggested_daily_rent_price;
        $investmentRequest->owner_note = $request->owner_note;
        $investmentRequest->status = InvestmentRequestStatus::Pending;
        $investmentRequest->save();
        
        $property = Property::find($request->property_id);
        // $property->status = PropertyStatus::InvestmentPending;
        $property->save();

        return back();
    }

    public function resubmit(Request $request){
        
        $request->validate([
            'request_id' => 'required|exists:investment_requests,id',
            'suggested_investment_amount' => 'required|numeric|min:0|max:1000000',
            'suggested_daily_rent_price' => 'required|numeric|min:0|max:10000',
            'owner_note' => 'max:1500',
        ]);

        $investmentRequest = InvestmentRequest::find($request->request_id);
        $investmentRequest->suggested_investment_amount = $request->suggested_investment_amount;
        $investmentRequest->suggested_daily_rent_price = $request->suggested_daily_rent_price;
        $investmentRequest->owner_note = $request->owner_note;
        $investmentRequest->status = InvestmentRequestStatus::Pending;
        $investmentRequest->save();
        
        $property = Property::find($investmentRequest->property_id);
        // $property->status = PropertyStatus::InvestmentPending;
        $property->save();

        return back();
    }
}
