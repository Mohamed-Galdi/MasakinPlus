<?php

namespace App\Http\Controllers\Owner;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->get('typeFilter') ?? '';
        $statusFilter = $request->get('statusFilter') ?? '';

        $properties = auth()->user()->ownedProperties()
            ->with([
                'amenities' => fn($query) => $query->select('amenities.id', 'name'),
                'images' => fn($query) => $query->select('property_images.id', 'property_id', 'path'),
            ])
            ->when($typeFilter && $typeFilter !== 'all', function ($query) use ($typeFilter) {
                $query->where('type', $typeFilter);
            })
            ->when($statusFilter && $statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $typeOptions = PropertyType::options();
        $statusOptions = PropertyStatus::options();

        return inertia('Owner/Properties/index', compact(
            'properties',
            'typeOptions',
            'statusOptions',
            'typeFilter',
            'statusFilter'
        ));
    }

    public function create()
    {
        $typeOptions = PropertyType::options();
        $amenities = Amenity::select('id', 'name')->get();
        return inertia('Owner/Properties/create', compact('typeOptions', 'amenities'));
    }

    public function store(Request $request)
    {

        dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:3',
            'type' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'bedrooms' => 'required|numeric|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'latitude' => 'required|numeric|min:0',
            'longitude' => 'required|numeric|min:0',
            'daily_rent_price' => 'required|numeric|min:0',
            'amenities' => 'required|array',
            'images' => 'required|array',
        ]);

        $property = new Property();
        $property->owner_id = auth()->user()->id;
        $property->title = $request->title;
        $property->description = $request->description;
        $property->type = $request->type;
        $property->type_label = PropertyType::labelFor($request->type);
        $property->city = $request->city;
        $property->address = $request->address;
        $property->area = $request->area;
        $property->bedrooms = $request->bedrooms;
        $property->bathrooms = $request->bathrooms;
        $property->latitude = $request->latitude;
        $property->longitude = $request->longitude;
        $property->daily_rent_price = $request->daily_rent_price;
        $property->save();

        foreach ($request->amenities as $amenity) {
            $property->amenities()->attach($amenity['id']);
        }

        $propertyImagesFolderName = 'property_' . $property->id . '_' . rand(100000, 999999);

        foreach ($request->images as $image) {

            $path = FileService::moveTempFile($image, "property_images/{$propertyImagesFolderName}", $property->id);

            $property->images()->create([
                'path' => $path,
            ]);
        }

        $property->save();

        return '';
    }
}
