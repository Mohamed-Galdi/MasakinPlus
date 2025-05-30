<?php

namespace Database\Seeders;

use App\Enums\PageSectionType;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aboutPage = Page::firstOrCreate(
            ['title' => 'about']
        );

        $hero =  [
            'title' => 'مساكن بلس',
            'subtitle' => 'نعيد تعريف الاستثمار العقاري في المملكة العربية السعودية من خلال منصة مبتكرة تربط بين أصحاب العقارات والمستثمرين والمستأجرين',
            'primaryButton' => [
                'text' => 'اكتشف رؤيتنا',
                'link' => '/about-us#mission'
            ],
            'secondaryButton' => [
                'text' => 'تواصل معنا',
                'link' => '/about-us#contact'
            ],
            'image' => '/storage/sections/about/about-default.jpeg',
            'terms' => [
                'term1' => 'منصة رائدة',
                'term2' => 'تقنية متطورة'
            ]
        ];

        $mission = [
            'title' => 'رؤيتنا و رسالتنا',
            'description' => 'نسعى في مساكن بلس إلى إحداث ثورة في سوق العقارات السعودي من خلال منصة مبتكرة تجمع بين أصحاب العقارات والمستثمرين والمستأجرين . هدفنا هو تسهيل الاستثمار العقاري وجعله متاحا للجميع، مع توفير خيارات سكنية متنوعة تلبي احتياجات المستأجرين .',
        ];

        $howItWorks = [
            "cards" => [
                [
                    'title' => "المستثمرون ",
                    'description' => "استثمر في العقارات بسهولة واستمتع بعوائد ثابتة دون الحاجة إلى إدارتها."
                ],
                [
                    'title' => "ملاك العقارات ",
                    'description' => "زد من عائدات عقارك بسهولة من خلال منصة موثوقة تربطك بالمستأجرين والمستثمرين."
                ],
                [
                    'title' => "المستأجرون ",
                    'description' => "اكتشف عقارات مميزة بأسعار تنافسية واستأجر منزلك الجديد بكل سهولة وأمان."
                ],
            ]
        ];

        $timeline = [
            'title' => 'رحلتنا',
            'milestones' => [
                [
                    'year' => '2022',
                    'title' => 'انطلاقة الرؤية',
                    'description' => 'بدأت فكرة مساكن بلس بهدف إحداث ثورة في سوق العقارات عبر حلول رقمية مبتكرة.'
                ],
                [
                    'year' => '2023',
                    'title' => 'إطلاق المنصة رسميا',
                    'description' => 'تم إطلاق منصتنا الرقمية، موفرةً تجربة سلسة لأصحاب العقارات والمستثمرين والمستأجرين.'
                ],
                [
                    'year' => '2024',
                    'title' => 'التوسع على مستوى المملكة',
                    'description' => 'وسعنا نطاق خدماتنا لتشمل مختلف المدن السعودية، مما عزز من فرص الاستثمار والتأجير.'
                ],
                [
                    'year' => '2025',
                    'title' => 'شراكات استراتيجية كبرى',
                    'description' => 'عقدنا تحالفات مع كبرى الشركات العقارية والمستثمرين لتعزيز نمو السوق وزيادة العوائد.'
                ]
            ]
        ];

        $team = [
            'title' => 'فريقنا',
            'team' => [
                [
                    'name' => 'أحمد الفيصل',
                    'position' => 'المؤسس والرئيس التنفيذي',
                    'image' => '/storage/sections/team/member-1/member-1-default.jpg',
                    'quote' => 'نسعى لتغيير مفهوم الاستثمار العقاري في المملكة'
                ],
                [
                    'name' => 'عبدالعزيز الدوسري',
                    'position' => 'مدير العمليات',
                    'image' => '/storage/sections/team/member-3/member-3-default.jpg',
                    'quote' => 'نبني جسرا بين أصحاب العقارات والمستثمرين'
                ],
                [
                    'name' => 'محمد القحطاني',
                    'position' => 'رئيس قسم التطوير',
                    'image' => '/storage/sections/team/member-2/member-2-default.jpg',
                    'quote' => 'التكنولوجيا هي مفتاح تبسيط الاستثمار العقاري'
                ],
                [
                    'name' => 'فهد المطيري',
                    'position' => 'مدير التسويق',
                    'image' => '/storage/sections/team/member-0/member-0-default.jpg',
                    'quote' => 'نصنع مستقبلا أفضل للسوق العقاري السعودي'
                ]
            ]
        ];

        $contact = [
            "cards" => [
                "address" => [
                    'type' => "العنوان",
                    'icon' => "pi pi-map-marker",
                    'title' => "الرياض، المملكة العربية السعودية",
                    'description' => "طريق الملك فهد"
                ],
                "phone" => [
                    'type' => "الهاتف",
                    'icon' => "pi pi-phone",
                    'title' => "+966 12 345 6789",
                    'description' => "من الأحد إلى الخميس، ٩ ص - ٥ م"
                ],
                "email" => [
                    'type' => "البريد الإلكتروني",
                    'icon' => "pi pi-envelope",
                    'title' => "info@masakin.com",
                    'description' => "تواصل معنا في أي وقت"
                ],
            ]
        ];

        PageSection::insert([
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::ABOUT_HERO,
                'content' => json_encode($hero),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::MISSION,
                'content' => json_encode($mission),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::HOW_IT_WORKS,
                'content' => json_encode($howItWorks),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::TIMELINE,
                'content' => json_encode($timeline),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::TEAM,
                'content' => json_encode($team),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $aboutPage->id,
                'section_type' => PageSectionType::CONTACT,
                'content' => json_encode($contact),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
