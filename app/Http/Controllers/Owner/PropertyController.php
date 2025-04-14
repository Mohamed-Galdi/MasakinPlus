<?php

namespace App\Http\Controllers\Owner;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;


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
}
