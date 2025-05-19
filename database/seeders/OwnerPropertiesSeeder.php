<?php

namespace Database\Seeders;

use App\Enums\PropertyStatus;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class OwnerPropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $properties = collect([
            ['owner_id' => 2, 'title' => 'فيلا فاخرة في الرياض', 'description' => 'فيلا واسعة بتصميم عصري في حي الملقا، مثالية للعائلات.', 'type' => 'فيلا', 'city' => 'الرياض', 'address' => 'حي الملقا، شارع الملك فهد', 'area' => 350.5, 'bedrooms' => 5, 'bathrooms' => 4, 'amenities' => ['واي فاي', 'موقف سيارات', 'مسبح']],
            ['owner_id' => 2, 'title' => 'شقة مريحة في جدة', 'description' => 'شقة مفروشة بالكامل في حي الحمراء، قريبة من الكورنيش.', 'type' => 'شقة', 'city' => 'جدة', 'address' => 'حي الحمراء، شارع فلسطين', 'area' => 120.0, 'bedrooms' => 2, 'bathrooms' => 2, 'amenities' => ['واي فاي', 'مكيفات']],
            ['owner_id' => 2, 'title' => 'استوديو في الدمام', 'description' => 'استوديو حديث للإيجار اليومي، مثالي للزوار.', 'type' => 'استوديو', 'city' => 'الدمام', 'address' => 'حي الشاطئ', 'area' => 50.0, 'bedrooms' => 1, 'bathrooms' => 1, 'amenities' => ['واي فاي', 'موقف سيارات']],
            ['owner_id' => 2, 'title' => 'منزل عائلي في مكة', 'description' => 'منزل مريح في حي العزيزية، قريب من الحرم.', 'type' => 'منزل', 'city' => 'مكة', 'address' => 'حي العزيزية', 'area' => 200.0, 'bedrooms' => 4, 'bathrooms' => 3, 'amenities' => ['مكيفات', 'حديقة']],
            ['owner_id' => 2, 'title' => 'مكتب في الرياض', 'description' => 'مكتب مجهز في مركز الأعمال بحي العليا.', 'type' => 'مكتب', 'city' => 'الرياض', 'address' => 'حي العليا، طريق الملك فهد', 'area' => 80.0, 'bedrooms' => 0, 'bathrooms' => 1, 'amenities' => ['واي فاي', 'مكيفات']],
            ['owner_id' => 2, 'title' => 'فيلا في الطائف', 'description' => 'فيلا هادئة محاطة بالطبيعة، مثالية للعطلات.', 'type' => 'فيلا', 'city' => 'الطائف', 'address' => 'حي الهدا', 'area' => 400.0, 'bedrooms' => 6, 'bathrooms' => 5, 'amenities' => ['حديقة', 'مسبح']],
            ['owner_id' => 2, 'title' => 'شقة فاخرة في الخبر', 'description' => 'شقة بإطلالة بحرية في حي العليا.', 'type' => 'شقة', 'city' => 'الخبر', 'address' => 'حي العليا', 'area' => 150.0, 'bedrooms' => 3, 'bathrooms' => 2, 'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية']],
            ['owner_id' => 2, 'title' => 'شقة في ينبع', 'description' => 'شقة قريبة من البحر في ينبع البحر.', 'type' => 'شقة', 'city' => 'ينبع', 'address' => 'حي الشربتلي', 'area' => 100.0, 'bedrooms' => 2, 'bathrooms' => 1, 'amenities' => ['موقف سيارات', 'مكيفات']],
            ['owner_id' => 2, 'title' => 'فيلا في المدينة', 'description' => 'فيلا فسيحة قريبة من المسجد النبوي.', 'type' => 'فيلا', 'city' => 'المدينة المنورة', 'address' => 'حي السلام', 'area' => 300.0, 'bedrooms' => 5, 'bathrooms' => 4, 'amenities' => ['واي فاي', 'مكيفات', 'حديقة']],
            ['owner_id' => 2, 'title' => 'استوديو في جدة', 'description' => 'استوديو صغير ومريح في حي الروضة.', 'type' => 'استوديو', 'city' => 'جدة', 'address' => 'حي الروضة', 'area' => 40.0, 'bedrooms' => 1, 'bathrooms' => 1, 'amenities' => ['واي فاي']],
            ['owner_id' => 2, 'title' => 'منزل في تبوك', 'description' => 'منزل عائلي في حي المروج.', 'type' => 'منزل', 'city' => 'تبوك', 'address' => 'حي المروج', 'area' => 180.0, 'bedrooms' => 3, 'bathrooms' => 2, 'amenities' => ['حديقة', 'موقف سيارات']],
            ['owner_id' => 2, 'title' => 'شقة في الرياض', 'description' => 'شقة حديثة في حي النخيل.', 'type' => 'شقة', 'city' => 'الرياض', 'address' => 'حي النخيل', 'area' => 130.0, 'bedrooms' => 3, 'bathrooms' => 2, 'amenities' => ['واي فاي', 'مسبح', 'صالة رياضية']],
        ]);

        $imagesToInsert = [];

        $properties->each(function ($data, $index) use (&$imagesToInsert) {
            $status = fake()->randomElement(PropertyStatus::cases());
            $data['status'] = $status->value;
            $data['daily_rent_price'] = in_array($status->value, [
                PropertyStatus::Draft->value,
                PropertyStatus::InvestmentRequested->value,
                PropertyStatus::InvestmentOfferSent->value,
            ]) ? null : round(fake()->numberBetween(100, 1000) / 50) * 50;

            $data['latitude'] = 24.7136;
            $data['longitude'] = 46.6753;

            $amenities = $data['amenities'];
            unset($data['amenities']);

            $property = Property::create($data);

            // Attach amenities
            $amenityIds = Amenity::whereIn('name', $amenities)->pluck('id');
            $property->amenities()->attach($amenityIds);

            // Prepare 3 images per property
            $id = $index + 1;
            for ($i = 1; $i <= 3; $i++) {
                $imagesToInsert[] = [
                    'property_id' => $property->id,
                    'path' => "storage/property_images/property_{$id}/p{$id}.{$i}.avif",
                    'order' => $i,
                ];
            }
        });

        // Bulk insert images
        PropertyImage::insert($imagesToInsert);
    }
}
