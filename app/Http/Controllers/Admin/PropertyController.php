<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->get('typeFilter');
        $statusFilter = $request->get('statusFilter');
        $cityFilter = $request->get('cityFilter');
        $search = $request->get('search');

        $properties = Property::with('owner')
            ->when($typeFilter, function ($query) use ($typeFilter) {
                $query->where('type', $typeFilter);
            })
            ->when($statusFilter, function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($cityFilter, function ($query) use ($cityFilter) {
                $query->where('city', $cityFilter);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhereHas('owner', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        $statusOptions = PropertyStatus::options();
        $typeOptions = PropertyType::options();

        return inertia('Admin/Properties/index', compact(
            'properties',
            'statusOptions',
            'typeOptions',
            'typeFilter',
            'statusFilter',
            'cityFilter',
            'search'
        ));
    }

    public function view(Request $request, Property $property)
    {
        $statusOptions = PropertyStatus::options();

        $property->load(['amenities', 'images', 'owner' => function ($query) {
            $query->withCount('ownedProperties');
        }]);

        return inertia('Admin/Properties/view', compact('property', 'statusOptions'));
    }
}
