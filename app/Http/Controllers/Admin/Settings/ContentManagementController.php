<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Enums\PageSectionType;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentManagementController extends Controller
{
    public function home()
    {
        $home = Page::where('title', 'home')->first();
        $hero = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::HERO)->select('id', 'content')->first();
        $usersCards = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::USERS_CARDS)->select('id', 'content')->first();
        $features = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::FEATURES)->select('id', 'content')->first();
        $ourNumbers = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::OUR_NUMBERS)->select('id', 'content')->first();
        $faqs = PageSection::where('page_id', $home->id)->where('section_type', PageSectionType::FAQS)->select('id', 'content')->first();
        return inertia('Admin/Settings/ContentManagement/home', compact('hero', 'usersCards', 'features', 'ourNumbers', 'faqs'));
    }

    public function about()
    {
        $about = Page::where('title', 'about')->first();
        $hero = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::ABOUT_HERO)->select('id', 'content')->first();
        $mission = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::MISSION)->select('id', 'content')->first();
        $howItWorks = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::HOW_IT_WORKS)->select('id', 'content')->first();
        $timeline = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::TIMELINE)->select('id', 'content')->first();
        $team = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::TEAM)->select('id', 'content')->first();
        $contact = PageSection::where('page_id', $about->id)->where('section_type', PageSectionType::CONTACT)->select('id', 'content')->first();
        return inertia('Admin/Settings/ContentManagement/about', compact('hero', 'mission', 'howItWorks', 'timeline', 'team', 'contact'));
    }

    public function privacy()
    {
        $content = Page::where('title', 'privacy')->first()->html_content;
        return inertia('Admin/Settings/ContentManagement/privacy', compact('content'));
    }

    public function updatePageContent(Request $request)
    {
        $page = Page::where('title', $request->page)->first();
        $page->html_content = $request->content;
        $page->save();
        return back()->with('success', 'تم تحديث الصفحة بنجاح');
    }

    public function updateSectionContent(Request $request)
    {
        $section = PageSection::findOrFail($request->section_id);

        // Define validation rules based on section_type
        $validationRules = $this->getValidationRules($section->section_type);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'required|array',
            ...$validationRules,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Get the content data
        $content = $request->input('content');

        // Process images based on section type
        $content = $this->processImages($content, $section->section_type);

        // Update the section
        $section->update([
            'content' => $content,
        ]);

        return back()->with('success', 'تم تحديث القسم بنجاح');
    }

    protected function getValidationRules(string $sectionType): array
    {
        $rules = [
            'hero' => [
                'content.slides' => 'required|array|min:1',
                'content.slides.*.type' => 'required|string|max:255',
                'content.slides.*.title' => 'required|string|max:255',
                'content.slides.*.description' => 'required|string',
                'content.slides.*.image' => 'nullable|string|max:255',
                'content.slides.*.buttonText' => 'required|string|max:255',
            ],
            'about-hero' => [
                'content.title' => 'required|string|max:255',
                'content.subtitle' => 'required|string|max:255',
                'content.primaryButton.text' => 'required|string|max:255',
                'content.primaryButton.link' => 'required|string|max:255',
                'content.secondaryButton.text' => 'required|string|max:255',
                'content.secondaryButton.link' => 'required|string|max:255',
                'content.image' => 'nullable|string|max:255',
                'content.terms.term1' => 'required|string|max:255',
                'content.terms.term2' => 'required|string|max:255',
            ],
            'users-cards' => [
                'content.cards' => 'required|array|min:1',
                'content.cards.*.title' => 'required|string|max:255',
                'content.cards.*.listItems' => 'required|array|min:1',
                'content.cards.*.listItems.*' => 'required|string|max:255',
                'content.cards.*.icon' => 'required|string|max:255',
            ],
            'features' => [
                'content.features' => 'required|array|min:1',
                'content.features.*.type' => 'required|string|max:255',
                'content.features.*.title' => 'required|string|max:255',
                'content.features.*.subtitle' => 'required|string|max:255',
                'content.features.*.image' => 'nullable|string|max:255',
                'content.features.*.cta' => 'required|string|max:255',
            ],
            'our-numbers' => [
                'content.stats' => 'required|array|min:1',
                'content.stats.*.label' => 'required|string|max:255',
                'content.stats.*.number' => 'required|numeric',
            ],
            'faqs' => [
                'content.questions' => 'required|array|min:1',
                'content.questions.*.question' => 'required|string|max:255',
                'content.questions.*.answer' => 'required|string|max:1500',
            ],
            'mission' => [
                'content.title' => 'required|string|max:255',
                'content.description' => 'required|string',
            ],
            'how-it-works' => [
                'content.cards' => 'required|array|min:1',
                'content.cards.*.title' => 'required|string|max:255',
                'content.cards.*.description' => 'required|string',
            ],
            'timeline' => [
                'content.title' => 'required|string|max:255',
                'content.milestones' => 'required|array|min:1',
                'content.milestones.*.year' => 'required|string|max:255',
                'content.milestones.*.title' => 'required|string|max:255',
                'content.milestones.*.description' => 'required|string',
            ],
            'team' => [
                'content.title' => 'required|string|max:255',
                'content.team' => 'required|array|min:1',
                'content.team.*.name' => 'required|string|max:255',
                'content.team.*.position' => 'required|string|max:255',
                'content.team.*.image' => 'nullable|string|max:255',
                'content.team.*.quote' => 'required|string|max:255',
            ],
            'contact' => [
                'content.cards.address.title' => 'required|string|max:255',
                'content.cards.address.description' => 'required|string|max:255',
                'content.cards.phone.title' => 'required|string|max:255',
                'content.cards.phone.description' => 'required|string|max:255',
                'content.cards.email.title' => 'required|string|max:255',
                'content.cards.email.description' => 'required|string|max:255',
            ],

        ];

        return $rules[$sectionType] ?? throw new \InvalidArgumentException("غير مدعوم: نوع القسم ");
    }
    protected function processImages(array $content, string $sectionType): array
    {
        switch ($sectionType) {
            case 'hero':
                if (isset($content['slides'])) {
                    foreach ($content['slides'] as $index => $slide) {
                        if (!empty($slide['image']) && !str_starts_with($slide['image'], '/storage/')) {
                            $content['slides'][$index]['image'] = '/' . $this->moveTempImage(
                                $slide['image'],
                                "sections/hero/slide-{$index}",
                                "hero-slide-{$index}"
                            );
                        }
                    }
                }
                break;
            case 'features':
                if (isset($content['features'])) {
                    foreach ($content['features'] as $index => $feature) {
                        if (!empty($feature['image']) && !str_starts_with($feature['image'], '/storage/')) {
                            $content['features'][$index]['image'] = '/' . $this->moveTempImage(
                                $feature['image'],
                                "sections/features/feature-{$index}",
                                "feature-{$index}"
                            );
                        }
                    }
                }
                break;
            case 'about-hero':
                if (isset($content['image'])) {
                    if (!str_starts_with($content['image'], '/storage/')) {
                        $content['image'] = '/' . $this->moveTempImage(
                            $content['image'],
                            "sections/about",
                            "about"
                        );
                    }
                }
                break;
            case 'team':
                if (isset($content['team'])) {
                    foreach ($content['team'] as $index => $member) {
                        if (!empty($member['image']) && !str_starts_with($member['image'], '/storage/')) {
                            $content['team'][$index]['image'] = '/' . $this->moveTempImage(
                                $member['image'],
                                "sections/team/member-{$index}",
                                "member-{$index}"
                            );
                        }
                    }
                }
                break;
        }

        return $content;
    }

    protected function moveTempImage(string $tempPath, string $destinationPath, string $title): ?string
    {
        return FileService::moveTempFile($tempPath, $destinationPath, $title);
    }
}
