<?php

namespace App\Http\Controllers;

use App\Enums\PageSectionType;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $home = Page::where('title', 'home')->first();
        $hero = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::HERO)->select('content')->first();

        return inertia('Home/Home', compact('hero'));
    }

    public function privacy()
    {
        return inertia('Home/PrivacyPolicy');
    }

    public function about()
    {
        return inertia('Home/AboutUs');
    }
}
