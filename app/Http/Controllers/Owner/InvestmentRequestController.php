<?php

namespace App\Http\Controllers\Owner;

use App\Enums\InvestmentRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $investmentRequests = $user->investmentRequests()->paginate(6);
        $investmentRequests->load('property');
        // $investmentRequests = [];

        $properties = Property::where('owner_id', $user->id)->where('status', 'draft')->whereDoesntHave('investmentRequests')->get();

        $InvestmentRequestStatusOptions = InvestmentRequestStatus::options();

        return inertia('Owner/InvestmentRequests/index', compact('properties', 'investmentRequests', 'InvestmentRequestStatusOptions'));
    }

    public function create(Request $request)
    {
        $request->validate([
            
        ]);
    }
}
