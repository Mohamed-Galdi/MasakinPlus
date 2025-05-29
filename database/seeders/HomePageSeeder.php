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
        $hero = [
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

        $usersCards = [
            [
                'title' => "لملاك العقارات",
                'listItems' => [
                    "وصول فوري للمستأجرين والمستثمرين الجادين",
                    "اعرض عقارك بطريقة احترافية وجذابة",
                    "تحكم كامل في التأجير والإدارة بكل بساطة",
                    "ضاعف أرباحك بأقل جهد وبدون وسيط",
                ],
                'icon' => "fa-solid fa-home",
            ],
            [
                'title' => "للمستثمرين",
                'listItems' => [
                    "حقق أرباحا مجزية باستثمارات مدروسة",
                    "استثمر دون الحاجة لإدارة العقارات بنفسك",
                    "استفد من فرص مضمونة بعوائد ثابتة",
                    "انضم إلى نخبة المستثمرين في السوق العقاري",
                ],
                'icon' => "fa-solid fa-coins",
            ],
            [
                'title' => "للباحث عن سكن",
                'listItems' => [
                    "ابحث بسهولة عن شقة تناسب احتياجاتك",
                    "احصل على أفضل الأسعار والعروض الحصرية",
                    "استمتع بتجربة بحث سلسة وسريعة",
                    "تواصل مباشرة مع الملاك والمستثمرين",
                ],
                'icon' => "fa-solid fa-user",
            ]
        ];

        $features = [
            [
                'type' => "Owners",
                'title' => "تريد تحقيق اقصى استفادة من عقارك؟",
                'subtitle' => "اعرض عقارك واستمتع بإدارة احترافية",
                'image' => '/storage/sections/features/feature-0/feature-0-default.svg',
                'cta' => "اعرض الآن",
            ],
            [
                'type' => "Investors",
                'title' => "تريد الاستثمار في العقار دون امتلاكه؟",
                'subtitle' => "استثمر بذكاء مع عوائد مضمونة",
                'image' =>  '/storage/sections/features/feature-1/feature-1-default.svg',
                'cta' => "استثمر الآن",
            ],
            [
                'type' => "Tenants",
                'title' => "تبحث عن أفضل سكن بأفضل ثمن؟",
                'subtitle' => "ابحث عن منزلك المثالي بسهولة",
                'image' =>  '/storage/sections/features/feature-2/feature-2-default.svg',
                'cta' => "ابحث الآن",
            ],
        ];

        // Create the page sections
        PageSection::insert([
            [
                'page_id' => $homePage->id,
                'section_type' => PageSectionType::HERO,
                'content' => json_encode(['slides' => $hero]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $homePage->id,
                'section_type' => PageSectionType::USERS_CARDS,
                'content' => json_encode(['cards' => $usersCards]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $homePage->id,
                'section_type' => PageSectionType::FEATURES,
                'content' => json_encode(['features' => $features]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
