<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $propertyStatusOptions = PropertyStatus::options();

        $propertyStatusDescriptions = collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [
            $status->value => $status->description(),
        ]);

        return inertia('Admin/Settings/index', compact('propertyStatusOptions', 'propertyStatusDescriptions'));
    }
}
