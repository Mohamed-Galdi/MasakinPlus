<?php

namespace Database\Seeders;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use App\Enums\UserType;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvestorPropertiesSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users who are owners, excluding user with id 2
        $ownerIds = User::where('type', UserType::Owner)
            ->where('id', '!=', 2)
            ->pluck('id')
            ->toArray();

        // If no other owners found, exit
        if (empty($ownerIds)) {
            $this->command->info('No other owners found. Please create some first.');

            return;
        }

        // If ownerIds is empty or not enough, use ID 12 as a fallback
        if (count($ownerIds) < 5) {
            $this->command->info('Not enough owners found. Adding ID 12 as a fallback.');
            $ownerIds[] = 12;
        }

        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة المنورة', 'الطائف', 'الخبر', 'تبوك', 'ينبع', 'الجبيل', 'حائل', 'نجران'];

        // Get available amenities from database
        $allAmenities = Amenity::pluck('id')->toArray();

        $neighborhoods = [
            'الرياض' => ['حي النخيل', 'حي الملقا', 'حي العليا', 'حي الورود', 'حي السليمانية', 'حي الياسمين', 'حي النزهة', 'حي لبن', 'حي الربوة'],
            'جدة' => ['حي الحمراء', 'حي الروضة', 'حي الشاطئ', 'حي السلامة', 'حي الصفا', 'حي النعيم', 'حي البوادي', 'حي الفيحاء'],
            'الدمام' => ['حي الشاطئ', 'حي الفيصلية', 'حي العزيزية', 'حي البحيرة', 'حي الروضة', 'حي الجلوية', 'حي المزروعية'],
            'مكة' => ['حي العزيزية', 'حي الشوقية', 'حي النسيم', 'حي العوالي', 'حي الزاهر', 'حي الرصيفة', 'حي العمرة'],
            'المدينة المنورة' => ['حي السلام', 'حي العنابس', 'حي النصر', 'حي الأزهري', 'حي قباء', 'حي العوالي', 'حي الدار'],
            'default' => ['حي الياسمين', 'حي الربيع', 'حي السلام', 'حي النزهة', 'حي الفيصلية'],
        ];

        // Collection of unique property titles for each type
        $titlePrefixes = [
            'شقة فاخرة',
            'شقة مفروشة',
            'شقة عائلية',
            'شقة دوبلكس',
            'شقة بنتهاوس',
            'فيلا مستقلة',
            'فيلا فاخرة',
            'فيلا مودرن',
            'فيلا عصرية',
            'فيلا درج صالة',
            'استوديو مفروش',
            'استوديو فاخر',
            'استوديو كلاسيك',
            'استوديو صغير',
            'مكتب إداري',
            'مكتب مجهز',
            'مكتب تجاري',
            'مكتب مفروش',
            'منزل واسع',
            'منزل مجدد',
            'منزل عائلي',
            'منزل تقليدي',
            'مبنى تجاري',
            'مبنى سكني',
            'مبنى إداري',
            'مبنى استثماري',
            'مستودع كبير',
            'مستودع مؤمن',
            'مستودع تجاري',
            'عقار تجاري متميز',
            'عقار تجاري استثماري',
            'أرض سكنية',
            'أرض تجارية',
            'أرض زراعية',
        ];

        // Collection of unique property descriptions
        $descriptionTemplates = [
            '{type} رائعة في موقع متميز، {features}، تتميز بـ{unique_feature}. {location_desc}',
            '{type} بتصميم عصري وإطلالة مميزة على {view}، {features}. مثالية لـ{target}.',
            '{type} فاخرة بمساحات واسعة ومرافق متكاملة، {features}. {location_desc}',
            '{type} مجددة بالكامل مع تشطيبات حديثة، {features}. {added_value}',
            '{type} مميزة في أرقى أحياء {city}، تمتاز بـ{unique_feature} و{features}. مناسبة لـ{target}.',
            '{type} فسيحة ومضاءة جيداً، {features}. {location_desc} {added_value}',
            '{view_desc} {type} مع مساحات رحبة، {features}. {security_desc}',
            '{type} بتصميم فريد، {features}، تحتوي على {unique_feature}. {added_value}',
            '{type} استثمارية ممتازة في {city}، {features}. العائد الاستثماري ممتاز.',
            '{type} في موقع استراتيجي في {neighborhood}، {features}. {location_desc}',
        ];

        $features = [
            'جميع الخدمات متوفرة بالقرب منها',
            'تصميم داخلي أنيق وعصري',
            'مساحات مفتوحة ومريحة',
            'نوافذ كبيرة تسمح بدخول الإضاءة الطبيعية',
            'شرفات واسعة',
            'مطبخ مجهز بالكامل',
            'غرف نوم فسيحة',
            'حمامات حديثة',
            'تكييف مركزي',
        ];

        $uniqueFeatures = [
            'تصميم مميز',
            'إطلالة خلابة',
            'موقع استراتيجي',
            'مساحة خضراء',
            'مسبح خاص',
            'جودة بناء عالية',
            'تشطيبات فاخرة',
            'نظام أمني متكامل',
            'عزل حراري ممتاز',
            'نظام ذكي للمنزل',
            'مصعد خاص',
            'مسطحات مائية',
            'مساحة ترفيهية',
        ];

        $views = [
            'المدينة',
            'الحديقة',
            'البحر',
            'الجبال',
            'الأفق',
            'الحي',
            'المنتزه',
            'البحيرة',
            'الشارع الرئيسي',
        ];

        $locationDesc = [
            'قريبة من المراكز التجارية والمدارس',
            'على بعد دقائق من وسط المدينة',
            'بالقرب من جميع الخدمات الأساسية',
            'تقع في منطقة هادئة وراقية',
            'على مقربة من المساجد والمدارس',
            'موقع استراتيجي قريب من الطرق الرئيسية',
            'قريبة من المستشفيات والمرافق الصحية',
            'محاطة بالمساحات الخضراء والحدائق',
        ];

        $addedValue = [
            'مثالية للاستثمار أو السكن',
            'تتميز بتكاليف صيانة منخفضة',
            'تم تجديدها بالكامل مؤخراً',
            'تحتوي على أنظمة توفير الطاقة',
            'الموقع يضمن خصوصية تامة',
            'سند ملكية جاهز ومفرز',
            'تتميز بتهوية طبيعية ممتازة',
        ];

        $securityDesc = [
            'مؤمنة بنظام مراقبة على مدار الساعة',
            'تقع في مجمع سكني آمن ومحروس',
            'مزودة بأنظمة أمان متطورة',
            'تحتوي على بوابات إلكترونية',
        ];

        $target = [
            'العائلات',
            'المستثمرين',
            'رجال الأعمال',
            'الأزواج الجدد',
            'الشركات الناشئة',
            'المستأجرين طويلي الأمد',
            'العوائل الكبيرة',
        ];

        $viewDesc = [
            'إطلالة بانورامية على',
            'إطلالة جزئية على',
            'إطلالة مباشرة على',
            'نوافذ تطل على',
        ];

        $imagesToInsert = [];
        $currentCount = Property::count(); // Get existing property count for image naming

        // Available property types from your enum
        $availablePropertyTypes = [
            PropertyType::Apartment,
            PropertyType::Villa,
            PropertyType::Studio,
            PropertyType::Office,
            PropertyType::House,
            PropertyType::Building,
            PropertyType::Warehouse,
            PropertyType::Commercial,
            PropertyType::Land,
        ];

        // Create 18 new properties
        for ($i = 1; $i <= 18; $i++) {
            $city = fake()->randomElement($cities);
            $propertyType = fake()->randomElement($availablePropertyTypes);
            $typeLabel = $propertyType->label(); // This is what we need to store in DB

            $neighborhoodsForCity = $neighborhoods[$city] ?? $neighborhoods['default'];
            $neighborhood = fake()->randomElement($neighborhoodsForCity);
            $ownerId = fake()->randomElement($ownerIds);

            // Generate unique title
            $titlePrefix = fake()->randomElement($titlePrefixes);
            $title = "{$titlePrefix} في {$neighborhood}، {$city}";

            // Generate unique description
            $descTemplate = fake()->randomElement($descriptionTemplates);
            $feature = fake()->randomElement($features);
            $uniqueFeature = fake()->randomElement($uniqueFeatures);
            $view = fake()->randomElement($views);
            $locDesc = fake()->randomElement($locationDesc);
            $addedVal = fake()->randomElement($addedValue);
            $secDesc = fake()->randomElement($securityDesc);
            $targetAudience = fake()->randomElement($target);
            $viewDescription = fake()->randomElement($viewDesc);

            $description = str_replace(
                [
                    '{type}',
                    '{features}',
                    '{unique_feature}',
                    '{location_desc}',
                    '{view}',
                    '{target}',
                    '{added_value}',
                    '{city}',
                    '{view_desc}',
                    '{security_desc}',
                    '{neighborhood}',
                ],
                [
                    $typeLabel,
                    $feature,
                    $uniqueFeature,
                    $locDesc,
                    $view,
                    $targetAudience,
                    $addedVal,
                    $city,
                    $viewDescription . ' ' . $view,
                    $secDesc,
                    $neighborhood,
                ],
                $descTemplate
            );

            // Bedroom count based on property type
            $bedrooms = match ($propertyType) {
                PropertyType::Villa, PropertyType::Building => fake()->numberBetween(4, 8),
                PropertyType::House => fake()->numberBetween(3, 6),
                PropertyType::Apartment => fake()->numberBetween(2, 4),
                PropertyType::Studio => 1,
                PropertyType::Office, PropertyType::Warehouse, PropertyType::Commercial, PropertyType::Land => 0,
            };

            // Bathroom count based on bedrooms and property type
            $bathrooms = match ($propertyType) {
                PropertyType::Land => 0,
                PropertyType::Studio => 1,
                PropertyType::Warehouse, PropertyType::Commercial, PropertyType::Office => fake()->numberBetween(1, 2),
                default => max(1, round($bedrooms * 0.75)),
            };

            // Area based on property type
            $area = match ($propertyType) {
                PropertyType::Villa => fake()->numberBetween(300, 600),
                PropertyType::Building => fake()->numberBetween(500, 2000),
                PropertyType::House => fake()->numberBetween(200, 400),
                PropertyType::Apartment => fake()->numberBetween(100, 180),
                PropertyType::Studio => fake()->numberBetween(40, 60),
                PropertyType::Office => fake()->numberBetween(70, 200),
                PropertyType::Warehouse => fake()->numberBetween(300, 1000),
                PropertyType::Commercial => fake()->numberBetween(150, 500),
                PropertyType::Land => fake()->numberBetween(500, 5000),
            };

            $status = $i < 4 ? PropertyStatus::InvestmentOfferSent->value : PropertyStatus::OpenForInvestment->value;

            $propertyData = [
                'owner_id' => $ownerId,
                'title' => $title,
                'description' => $description,
                'type' => $typeLabel,
                'city' => $city,
                'address' => "{$neighborhood}، {$city}",
                'area' => $area,
                'bedrooms' => $bedrooms,
                'bathrooms' => $bathrooms,
                'status' => $status,
                'nightly_rent' => round(fake()->numberBetween(200, 1500) / 50) * 50,
                'latitude' => fake()->randomFloat(4, 24.0, 26.0),
                'longitude' => fake()->randomFloat(4, 45.0, 50.0),
            ];

            // Add investment-related fields only for OpenForInvestment status
            if ($status === PropertyStatus::OpenForInvestment->value) {
                $valuation = round(fake()->numberBetween(500000, 800000) / 10000) * 10000;
                $investmentRequired = round(fake()->numberBetween(80000, 120000) / 10000) * 10000;
                $monthlyOperatingCost = round(fake()->numberBetween(2000, 4000) / 100) * 100;

                // Generate shares ensuring total = 100%
                $platformShare = fake()->randomFloat(2, 0.03, 0.12); // 3% to 12%
                $ownerShare = fake()->randomFloat(2, 0.65, 0.85); // 65% to 85%
                $investorShare = round(1.00 - $ownerShare - $platformShare, 2); // Remaining percentage

                // Ensure investor share is positive (adjust if needed)
                if ($investorShare < 0.05) {
                    $ownerShare = 1.00 - $platformShare - 0.05;
                    $investorShare = 0.05;
                }

                $propertyData = array_merge($propertyData, [
                    'valuation' => $valuation,
                    'investment_required' => $investmentRequired,
                    'monthly_operating_cost' => $monthlyOperatingCost,
                    'owner_share' => $ownerShare,
                    'investor_share' => $investorShare,
                    'platform_fee_share' => $platformShare,
                ]);
            }

            $property = Property::create($propertyData);

            // Attach random amenities (between 3-6 amenities per property)
            if (! empty($allAmenities)) {
                $amenityCount = fake()->numberBetween(3, min(6, count($allAmenities)));
                $selectedAmenities = fake()->randomElements($allAmenities, $amenityCount);
                $property->amenities()->attach($selectedAmenities);
            }

            // Prepare 3 images per property
            $imageIndex = $currentCount + $i;
            for ($j = 1; $j <= 3; $j++) {
                $imagesToInsert[] = [
                    'property_id' => $property->id,
                    'path' => "storage/property_images/property_{$imageIndex}/p{$imageIndex}.{$j}.avif",
                    'order' => $j,
                ];
            }
        }

        // Bulk insert images
        if (! empty($imagesToInsert)) {
            PropertyImage::insert($imagesToInsert);
        }
    }
}
