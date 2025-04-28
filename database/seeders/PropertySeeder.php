<?php

namespace Database\Seeders;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Amenity;
use App\Models\PropertyImage;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $status = PropertyStatus::Draft->value;
        // Sample property data (12 properties)
        $properties = [
            [
                'owner_id' => 2,
                'title' => 'فيلا فاخرة في الرياض',
                'description' => 'فيلا واسعة بتصميم عصري في حي الملقا، مثالية للعائلات.',
                'type_ar' => 'فيلا',
                'city' => 'الرياض',
                'address' => 'حي الملقا، شارع الملك فهد',
                'area' => 350.5,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'موقف سيارات', 'مسبح'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة مريحة في جدة',
                'description' => 'شقة مفروشة بالكامل في حي الحمراء، قريبة من الكورنيش.',
                'type_ar' => 'شقة',
                'city' => 'جدة',
                'address' => 'حي الحمراء، شارع فلسطين',
                'area' => 120.0,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'استوديو في الدمام',
                'description' => 'استوديو حديث للإيجار اليومي، مثالي للزوار.',
                'type_ar' => 'استوديو',
                'city' => 'الدمام',
                'address' => 'حي الشاطئ',
                'area' => 50.0,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'موقف سيارات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'منزل عائلي في مكة',
                'description' => 'منزل مريح في حي العزيزية، قريب من الحرم.',
                'type_ar' => 'منزل',
                'city' => 'مكة',
                'address' => 'حي العزيزية',
                'area' => 200.0,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['مكيفات', 'حديقة'],
            ],

            [
                'owner_id' => 2,
                'title' => 'مكتب في الرياض',
                'description' => 'مكتب مجهز في مركز الأعمال بحي العليا.',
                'type_ar' => 'مكتب',
                'city' => 'الرياض',
                'address' => 'حي العليا، طريق الملك فهد',
                'area' => 80.0,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'فيلا في الطائف',
                'description' => 'فيلا هادئة محاطة بالطبيعة، مثالية للعطلات.',
                'type_ar' => 'فيلا',
                'city' => 'الطائف',
                'address' => 'حي الهدا',
                'area' => 400.0,
                'bedrooms' => 6,
                'bathrooms' => 5,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['حديقة', 'مسبح'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة فاخرة في الخبر',
                'description' => 'شقة بإطلالة بحرية في حي العليا.',
                'type_ar' => 'شقة',
                'city' => 'الخبر',
                'address' => 'حي العليا',
                'area' => 150.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة في ينبع',
                'description' => 'شقة قريبة من البحر في ينبع البحر.',
                'type_ar' => 'شقة',
                'city' => 'ينبع',
                'address' => 'حي الشربتلي',
                'area' => 100.0,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['موقف سيارات', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'فيلا في المدينة',
                'description' => 'فيلا فسيحة قريبة من المسجد النبوي.',
                'type_ar' => 'فيلا',
                'city' => 'المدينة المنورة',
                'address' => 'حي السلام',
                'area' => 300.0,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'مكيفات', 'حديقة'],
            ],
            [
                'owner_id' => 2,
                'title' => 'استوديو في جدة',
                'description' => 'استوديو صغير ومريح في حي الروضة.',
                'type_ar' => 'استوديو',
                'city' => 'جدة',
                'address' => 'حي الروضة',
                'area' => 40.0,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي'],
            ],
            [
                'owner_id' => 2,
                'title' => 'منزل في تبوك',
                'description' => 'منزل عائلي في حي المروج.',
                'type_ar' => 'منزل',
                'city' => 'تبوك',
                'address' => 'حي المروج',
                'area' => 180.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['حديقة', 'موقف سيارات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة في الرياض',
                'description' => 'شقة حديثة في حي النخيل.',
                'type_ar' => 'شقة',
                'city' => 'الرياض',
                'address' => 'حي النخيل',
                'area' => 130.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => $status,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية'],
            ],
        ];

        foreach ($properties as $key => $data) {

            // Match Arabic name to PropertyType enum
            $typeEnum = match ($data['type_ar']) {
                'شقة' => PropertyType::Apartment,
                'فيلا' => PropertyType::Villa,
                'استوديو' => PropertyType::Studio,
                'مكتب' => PropertyType::Office,
                'منزل' => PropertyType::House,
                'مبنى' => PropertyType::Building,
                'مستودع' => PropertyType::Warehouse,
                'عقار تجاري' => PropertyType::Commercial,
                'أرض' => PropertyType::Land,
                default => PropertyType::Apartment,
            };

            $statusEnum = fake()->randomElement(PropertyStatus::cases());

            // Add new enum-based columns
            $data['type'] = $typeEnum->value;
            $data['type_label'] = $typeEnum->label();
            $data['status'] = $statusEnum->value;
            $data['status_label'] = $statusEnum->label();
            $data['daily_rent_price'] = in_array($statusEnum->value, [
                PropertyStatus::Draft->value,
                PropertyStatus::InvestmentPending->value,
                PropertyStatus::InvestmentRejected->value
            ])
                ? null
                : round(fake()->numberBetween(100, 1000) / 50) * 50; // SAR 100 to 1,000 daily

            
            unset($data['type_ar']); // Remove helper key


            // Store amenities separately
            $amenities = $data['amenities'];
            unset($data['amenities']);

            // Create property
            $property = Property::create($data);

            // Attach amenities
            $amenityIds = Amenity::whereIn('name', $amenities)->pluck('id');
            $property->amenities()->attach($amenityIds);



            // Create images (3 per property)

            $id = $key + 1;
            for ($i = 1; $i <= 3; $i++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => "storage/property_images/property_{$id}/p{$id}.{$i}.avif",
                    'order' => $i,
                ]);
            }
        }
    }
}
