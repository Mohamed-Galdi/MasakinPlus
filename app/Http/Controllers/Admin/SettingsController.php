<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PageSectionType;
use App\Enums\PropertyStatus;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.content.home');
    }

    // ################################## Content Management ##################################
    public function home()
    {
        $home = Page::where('title', 'home')->first();
        $hero = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::HERO)->select('content')->first();

        // dd($hero);

        return inertia('Admin/Settings/ContentManagement/home', compact('hero'));
    }

    public function about()
    {
        return inertia('Admin/Settings/ContentManagement/about');
    }

    public function privacy()
    {
        return inertia('Admin/Settings/ContentManagement/privacy');
    }
    // ########################################################################################### 

    public function propertyStatusGuide(Request $request)
    {
        $propertyStatusOptions = PropertyStatus::options();

        $propertyStatusDescriptions = collect(PropertyStatus::cases())->mapWithKeys(fn ($status) => [
            $status->value => $status->description(),
        ]);

        return inertia('Admin/Settings/property_status_guide', compact('propertyStatusOptions', 'propertyStatusDescriptions'));
    }

    public function settingTab()
    {

        return inertia('Admin/Settings/settings_tab');
    }

}
