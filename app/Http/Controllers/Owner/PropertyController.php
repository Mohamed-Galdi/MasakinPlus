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
            ->limit(3)
            ->get();

        return inertia('Owner/Properties/index', compact('properties'));
    }

    public function create()
    {
        return inertia('Owner/Properties/create');
    }
}
