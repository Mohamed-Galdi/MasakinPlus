<?php

namespace App\Http\Controllers\Owner;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->get('typeFilter') ?? '';
        $statusFilter = $request->get('statusFilter') ?? '';

        $properties = auth()->user()->ownedProperties()
            ->with([
                'amenities' => fn ($query) => $query->select('amenities.id', 'name'),
                'images' => fn ($query) => $query->select('property_images.id', 'property_id', 'path'),
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
            'amenities' => 'array',
            'images' => 'required|array',
        ]);

        $property = new Property;
        $property->owner_id = auth()->user()->id;
        $property->title = $request->title;
        $property->description = $request->description;
        $property->type = $request->type;
        $property->city = $request->city;
        $property->address = $request->address;
        $property->area = $request->area;
        $property->bedrooms = $request->bedrooms;
        $property->bathrooms = $request->bathrooms;
        $property->latitude = $request->latitude;
        $property->longitude = $request->longitude;
        $property->save();

        foreach ($request->amenities as $amenity) {
            $property->amenities()->attach($amenity['id']);
        }

        $propertyImagesFolderName = 'property_'.$property->id.'_'.Str::random(6);

        foreach ($request->images as $image) {

            $path = FileService::moveTempFile($image, "property_images/{$propertyImagesFolderName}", $property->id);

            $property->images()->create([
                'path' => $path,
            ]);
        }

        $property->save();

        return '';
    }

    public function edit(Request $request, Property $property)
    {
        // authorization
        Gate::authorize('update', $property);

        $typeOptions = PropertyType::options();
        $amenities = Amenity::select('id', 'name')->get();

        $property->load('amenities', 'images');

        return inertia('Owner/Properties/update', compact('property', 'typeOptions', 'amenities'));
    }

    public function update(Request $request, Property $property)
    {
        // authorization
        Gate::authorize('update', $property);

        // Validate request data
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
            'amenities' => 'array',
            'images' => 'required|array|min:1|max:5',
            'newImages' => 'nullable|array',
            'removedImages' => 'nullable|array',
        ]);

        // Perform all updates within a transaction
        return DB::transaction(function () use ($request, $property) {
            // Update property details
            $property->update([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'city' => $request->city,
                'address' => $request->address,
                'area' => $request->area,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => PropertyStatus::Draft->value,
            ]);

            // Sync amenities
            $property->amenities()->sync(array_column($request->amenities, 'id'));

            // Handle removed images
            if ($request->removedImages) {
                // Fix path format to match disk structure
                $imagePaths = array_map(function ($path) {
                    return preg_replace('#^/?storage/#', '', $path);
                }, $request->removedImages);

                // Bulk delete from database
                $property->images()->whereIn('path', array_map(fn ($path) => 'storage/'.$path, $imagePaths))->delete();

                // Delete from storage
                foreach ($imagePaths as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);

                        // Clean up empty folders
                        $folderPath = dirname($imagePath);
                        if (
                            Storage::disk('public')->exists($folderPath) &&
                            count(Storage::disk('public')->files($folderPath)) === 0
                        ) {
                            Storage::disk('public')->deleteDirectory($folderPath);
                        }
                    } else {
                        Log::warning("Image not found in storage: {$imagePath}");
                    }
                }
            }

            // Handle new images
            if ($request->newImages) {
                $folderName = $this->getImageFolder($request->images, $property->id);
                foreach ($request->newImages as $image) {
                    // Corrected parameter order to match FileService
                    $path = FileService::moveTempFile($image, "property_images/{$folderName}", $property->id);
                    if ($path) {
                        $property->images()->create(['path' => $path]);
                    } else {
                        Log::warning("Failed to move temporary file: {$image}");
                    }
                }
            }

            return '';
        });
    }

    /**
     * Get the folder name for storing images, reusing the folder of existing images if available.
     */
    private function getImageFolder(array $images, string $propertyId): string
    {
        // Extract only the non-temporary images (those that already have paths)
        $existingImages = array_filter($images, function ($image) {
            return is_string($image) && strpos($image, 'storage/property_images/') !== false;
        });

        // If we have existing images, extract the folder name from the first one
        if (! empty($existingImages)) {
            $firstImage = reset($existingImages);

            // Extract folder name using regex - matches the pattern in storage/property_images/FOLDER_NAME/filename
            if (preg_match('#property_images/([^/]+)/#', $firstImage, $matches)) {
                return $matches[1];
            }
        }

        // If no existing folder found, create a new one with a simpler unique name
        return 'property_'.$propertyId.'_'.Str::random(8);
    }
}
