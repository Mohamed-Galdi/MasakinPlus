<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Amenity;
use App\Models\PropertyImage;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // Sample property data (12 properties)
        $properties = [
            [
                'owner_id' => 2,
                'title' => 'فيلا فاخرة في الرياض',
                'description' => 'فيلا واسعة بتصميم عصري في حي الملقا، مثالية للعائلات.',
                'type' => 'فيلا',
                'city' => 'الرياض',
                'address' => 'حي الملقا، شارع الملك فهد',
                'area' => 350.5,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'is_furnished' => true,
                'daily_rent_price' => 1200.00,
                'status' => 'approved',
                'amenities' => ['واي فاي', 'موقف سيارات', 'مسبح'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة مريحة في جدة',
                'description' => 'شقة مفروشة بالكامل في حي الحمراء، قريبة من الكورنيش.',
                'type' => 'شقة',
                'city' => 'جدة',
                'address' => 'حي الحمراء، شارع فلسطين',
                'area' => 120.0,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'is_furnished' => true,
                'daily_rent_price' => 300.00,
                'status' => 'pending',
                'amenities' => ['واي فاي', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'استوديو في الدمام',
                'description' => 'استوديو حديث للإيجار اليومي، مثالي للزوار.',
                'type' => 'استوديو',
                'city' => 'الدمام',
                'address' => 'حي الشاطئ',
                'area' => 50.0,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'is_furnished' => false,
                'daily_rent_price' => 150.00,
                'status' => 'approved',
                'amenities' => ['واي فاي', 'موقف سيارات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'منزل عائلي في مكة',
                'description' => 'منزل مريح في حي العزيزية، قريب من الحرم.',
                'type' => 'منزل',
                'city' => 'مكة',
                'address' => 'حي العزيزية',
                'area' => 200.0,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'is_furnished' => false,
                'daily_rent_price' => 600.00,
                'status' => 'funding',
                'amenities' => ['مكيفات', 'حديقة'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة فاخرة في الخبر',
                'description' => 'شقة بإطلالة بحرية في حي العليا.',
                'type' => 'شقة',
                'city' => 'الخبر',
                'address' => 'حي العليا',
                'area' => 150.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'is_furnished' => true,
                'daily_rent_price' => 400.00,
                'status' => 'rented',
                'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية'],
            ],
            [
                'owner_id' => 2,
                'title' => 'فيلا في الطائف',
                'description' => 'فيلا هادئة محاطة بالطبيعة، مثالية للعطلات.',
                'type' => 'فيلا',
                'city' => 'الطائف',
                'address' => 'حي الهدا',
                'area' => 400.0,
                'bedrooms' => 6,
                'bathrooms' => 5,
                'is_furnished' => false,
                'daily_rent_price' => 1000.00,
                'status' => 'approved',
                'amenities' => ['حديقة', 'مسبح'],
            ],
            [
                'owner_id' => 2,
                'title' => 'مكتب في الرياض',
                'description' => 'مكتب مجهز في مركز الأعمال بحي العليا.',
                'type' => 'مكتب',
                'city' => 'الرياض',
                'address' => 'حي العليا، طريق الملك فهد',
                'area' => 80.0,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'is_furnished' => true,
                'daily_rent_price' => 500.00,
                'status' => 'pending',
                'amenities' => ['واي فاي', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة في ينبع',
                'description' => 'شقة قريبة من البحر في ينبع البحر.',
                'type' => 'شقة',
                'city' => 'ينبع',
                'address' => 'حي الشربتلي',
                'area' => 100.0,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'is_furnished' => false,
                'daily_rent_price' => 200.00,
                'status' => 'approved',
                'amenities' => ['موقف سيارات', 'مكيفات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'فيلا في المدينة',
                'description' => 'فيلا فسيحة قريبة من المسجد النبوي.',
                'type' => 'فيلا',
                'city' => 'المدينة المنورة',
                'address' => 'حي السلام',
                'area' => 300.0,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'is_furnished' => true,
                'daily_rent_price' => 900.00,
                'status' => 'rented',
                'amenities' => ['واي فاي', 'مكيفات', 'حديقة'],
            ],
            [
                'owner_id' => 2,
                'title' => 'استوديو في جدة',
                'description' => 'استوديو صغير ومريح في حي الروضة.',
                'type' => 'استوديو',
                'city' => 'جدة',
                'address' => 'حي الروضة',
                'area' => 40.0,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'is_furnished' => true,
                'daily_rent_price' => 120.00,
                'status' => 'approved',
                'amenities' => ['واي فاي'],
            ],
            [
                'owner_id' => 2,
                'title' => 'منزل في تبوك',
                'description' => 'منزل عائلي في حي المروج.',
                'type' => 'منزل',
                'city' => 'تبوك',
                'address' => 'حي المروج',
                'area' => 180.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'is_furnished' => false,
                'daily_rent_price' => 350.00,
                'status' => 'inactive',
                'amenities' => ['حديقة', 'موقف سيارات'],
            ],
            [
                'owner_id' => 2,
                'title' => 'شقة في الرياض',
                'description' => 'شقة حديثة في حي النخيل.',
                'type' => 'شقة',
                'city' => 'الرياض',
                'address' => 'حي النخيل',
                'area' => 130.0,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'is_furnished' => true,
                'daily_rent_price' => 450.00,
                'status' => 'approved',
                'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية'],
            ],
        ];

        foreach ($properties as $data) {
            // Store amenities separately
            $amenities = $data['amenities'];
            unset($data['amenities']);

            // Create property
            $property = Property::create($data);

            // Attach amenities
            $amenityIds = Amenity::whereIn('name', $amenities)->pluck('id');
            $property->amenities()->attach($amenityIds);

            // Create images (3 per property)
            for ($i = 1; $i <= 3; $i++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => "property_images/property_{$property->id}/image_{$i}.jpg",
                    'order' => $i,
                ]);
            }
        }
    }
}
