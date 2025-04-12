<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PropertyController extends Controller
{
    public function index()
    {
        $properties = auth()->user()->ownedProperties()
            ->with([
                'amenities' => fn($query) => $query->select('amenities.id', 'name'),
                'images' => fn($query) => $query->select('property_images.id', 'property_id', 'path'),
            ])
            ->get();

        return inertia('Owner/Properties', compact('properties'));
    }
}
