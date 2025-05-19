<?php

namespace App\Http\Controllers\Investor;

use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\Property;

class OfferController extends Controller
{
    public function index()
    {
        $properties = Property::where('status', PropertyStatus::OpenForInvestment)->get();

        return inertia('Investor/Offers/index', compact('properties'));
    }
}
