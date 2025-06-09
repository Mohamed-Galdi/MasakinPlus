<?php

namespace App\Http\Controllers\Investor;

use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\Property;

class OfferController extends Controller
{
    public function index()
    {
        // $properties = Property::with('images', 'amenities')->paginate(100);
        $properties = Property::where('status', PropertyStatus::OpenForInvestment)->with('images', 'amenities')->paginate(9);

        return inertia('Investor/Offers/index', compact('properties'));
    }
    public function show($id)
    {
        $property = Property::findOrFail($id);

        $property->load('images', 'amenities');

        return inertia('Investor/Offers/show', compact('property'));
    }
}
