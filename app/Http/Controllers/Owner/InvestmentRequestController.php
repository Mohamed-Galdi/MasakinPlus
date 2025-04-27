<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class InvestmentRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $investmentRequests = $user->investmentRequests()->paginate(6);
        $properties = Property::where('owner_id', $user->id)->where('status', 'draft')->paginate(6);

        return inertia('Owner/InvestmentRequests/index', compact('properties', 'investmentRequests'));
    }
}
