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
        $sectionTypes = [
            PageSectionType::HERO,
            PageSectionType::USERS_CARDS,
            PageSectionType::FEATURES,
            PageSectionType::OUR_NUMBERS,
            PageSectionType::FAQS,
        ];

        $sections = $this->getPageSections('home', $sectionTypes);

        return inertia('Home/Home', [
            'hero' => $sections[PageSectionType::HERO->value] ?? null,
            'usersCards' => $sections[PageSectionType::USERS_CARDS->value] ?? null,
            'features' => $sections[PageSectionType::FEATURES->value] ?? null,
            'ourNumbers' => $sections[PageSectionType::OUR_NUMBERS->value] ?? null,
            'faqs' => $sections[PageSectionType::FAQS->value] ?? null,
        ]);
    }

    public function privacy()
    {
        $page = Page::where('title', 'privacy')->first();

        if (!$page) {
            abort(404, 'Privacy page not found');
        }

        return inertia('Home/PrivacyPolicy', [
            'content' => $page->html_content
        ]);
    }

    public function about()
    {
        $sectionTypes = [
            PageSectionType::ABOUT_HERO,
            PageSectionType::MISSION,
            PageSectionType::HOW_IT_WORKS,
            PageSectionType::TIMELINE,
            PageSectionType::TEAM,
            PageSectionType::CONTACT,
        ];

        $sections = $this->getPageSections('about', $sectionTypes);

        return inertia('Home/AboutUs', [
            'hero' => $sections[PageSectionType::ABOUT_HERO->value] ?? null,
            'mission' => $sections[PageSectionType::MISSION->value] ?? null,
            'howItWorks' => $sections[PageSectionType::HOW_IT_WORKS->value] ?? null,
            'timeline' => $sections[PageSectionType::TIMELINE->value] ?? null,
            'team' => $sections[PageSectionType::TEAM->value] ?? null,
            'contact' => $sections[PageSectionType::CONTACT->value] ?? null,
        ]);
    }

    /**
     * Get page sections by page title and section types
     */
    private function getPageSections(string $pageTitle, array $sectionTypes): array
    {
        $page = Page::where('title', $pageTitle)->first();

        if (!$page) {
            abort(404, ucfirst($pageTitle) . ' page not found');
        }

        return PageSection::where('page_id', $page->id)
            ->whereIn('section_type', $sectionTypes)
            ->select('section_type', 'content')
            ->get()
            ->keyBy('section_type')
            ->toArray();
    }
}
