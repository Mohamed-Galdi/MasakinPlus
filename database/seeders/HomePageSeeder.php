<?php

namespace Database\Seeders;

use App\Enums\PageSectionType;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Home page
        $homePage = Page::firstOrCreate(
            ['title' => 'home']
        );

        // Define the Hero section slides
        $slides = [
            [
                'type' => 'Property Owners',
                'title' => 'حقق اقصى استفادة من عقارك',
                'description' => 'اجعل عقارك يبرز في السوق! انضم الى منصة متخصصة تربطك بالمستاجرين والمستثمرين بسهولة',
                'image' => '/storage/sections/hero/slide-0/hero-slide-0-default.jpg',
                'buttonText' => 'سجل عقارك الان',
            ],
            [
                'type' => 'Investors',
                'title' => 'فرص عقارية ذهبية بانتظارك',
                'description' => 'استثمارات مدروسة وعوائد مضمونة! استثمر في عقارات مدارة بذكاء لتحقق ارباحا مستدامة.',
                'image' => '/storage/sections/hero/slide-1/hero-slide-1-default.jpg',
                'buttonText' => 'اكتشف افضل الفرص العقارية',
            ],
            [
                'type' => 'Tenants',
                'title' => 'سكنك المثالي بانتظارك',
                'description' => 'اسهل طريقة للعثور على بيتك المثالي! تصفح خيارات متنوعة واحجز مسكنك في دقائق',
                'image' => '/storage/sections/hero/slide-2/hero-slide-2-default.jpg',
                'buttonText' => 'اكتشف افضل العروض',
            ],
        ];

        // Create the Hero section
        PageSection::create([
            'page_id' => $homePage->id,
            'section_type' => PageSectionType::HERO,
            'content' => [
                'slides' => $slides
            ],
        ]);
    }
}
