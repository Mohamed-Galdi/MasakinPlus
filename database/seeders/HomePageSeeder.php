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

        $ourNumbers = [
            [
                'label' => "لدينا حتى الآن أكثر من 45 مالك راضٍ",
                'number' => 45,
                'icon' => "fa-solid fa-home",
            ],
            [
                'label' => "لدينا أكثر من 68 مستثمر ناجح",
                'number' => 68,
                'icon' => "fa-solid fa-coins",
            ],
            [
                'label' => "لدينا أكثر من 180 مستأجر سعيد",
                'number' => 180,
                'icon' => "fa-solid fa-user",
            ],
        ];

        $questions = [
            [
                'question'=> "كيف يمكنني عرض عقاري على المنصة؟",
                'answer'=> "يمكنك تسجيل الدخول إلى حسابك، ثم اختيار 'إضافة عقار' وإدخال التفاصيل المطلوبة مثل الموقع، السعر، والصور. بعد الموافقة عليه، سيتم عرضه للمستخدمين.",
            ],
            [
                'question'=> "هل يمكنني الاستثمار في العقارات دون امتلاكها؟",
                'answer'=> "نعم، توفر منصتنا فرص استثمار عقاري حيث يمكنك المساهمة في تمويل العقارات والاستفادة من العوائد دون الحاجة إلى إدارتها بنفسك.",
            ],
            [
                'question'=> "ما هي طرق الدفع المتاحة عند استئجار عقار؟",
                'answer'=> "نقبل عدة طرق للدفع، بما في ذلك التحويل البنكي، البطاقات الائتمانية، والمدفوعات الإلكترونية عبر المنصة لضمان عملية آمنة وسهلة.",
            ],
            [
                'question'=> "كيف يتم حساب العائد على الاستثمار؟",
                'answer'=> "يتم حساب العائد بناءً على نسبة مساهمتك في العقار، وإجمالي العوائد الشهرية المحصلة من الإيجارات، مطروحا منها التكاليف التشغيلية والإدارية.",
            ],
            [
                'question'=> "هل يوجد رسوم عند نشر عقار على المنصة؟",
                'answer'=> "نشر العقارات مجاني، ولكن هناك رسوم خدمة بسيطة عند إتمام عملية التأجير أو الاستثمار لضمان استمرارية المنصة وتحسين الخدمات.",
            ],
            [
                'question'=> "كيف يمكنني التواصل مع مالك العقار؟",
                'answer'=> "يمكنك إرسال رسالة مباشرة إلى مالك العقار من خلال صفحة العقار على المنصة، كما يمكنك استخدام ميزة الدردشة الفورية إن كانت متاحة.",
            ],
            [
                'question'=> "هل يمكنني إلغاء عقد الإيجار قبل انتهائه؟",
                'answer'=> "ذلك يعتمد على شروط العقد. يُفضل مراجعة العقد أو التواصل مع المالك لمعرفة الخيارات المتاحة لك.",
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
            [
                'page_id' => $homePage->id,
                'section_type' => PageSectionType::OUR_NUMBERS,
                'content' => json_encode(['stats' => $ourNumbers]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_id' => $homePage->id,
                'section_type' => PageSectionType::FAQS,
                'content' => json_encode(['questions' => $questions]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
