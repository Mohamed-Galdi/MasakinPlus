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
        return inertia('Admin/Settings/ContentManagement/home', compact('hero', 'usersCards', 'features'));
    }

    public function about()
    {
        return inertia('Admin/Settings/ContentManagement/about');
    }

    public function privacy()
    {
        return inertia('Admin/Settings/ContentManagement/privacy');
    }

    public function updatePageContent(Request $request)
    {
        $page = Page::find($request->page_id);
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
        }

        return $content;
    }

    protected function moveTempImage(string $tempPath, string $destinationPath, string $title): ?string
    {
        return FileService::moveTempFile($tempPath, $destinationPath, $title);
    }
}
