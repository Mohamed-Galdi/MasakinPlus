<?php

use App\Models\Amenity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Seed default Arabic amenities
        $amenities = [
            ['name' => 'واي فاي', 'icon' => 'fa-solid fa-wifi'],
            ['name' => 'موقف سيارات', 'icon' => 'fa-solid fa-square-parking'],
            ['name' => 'مكيفات', 'icon' => 'fa-solid fa-fan'],
            ['name' => 'حديقة', 'icon' => 'fa-solid fa-seedling'],
            ['name' => 'مسبح', 'icon' => 'fa-solid fa-person-swimming'],
            ['name' => 'صالة رياضية', 'icon' => 'fa-solid fa-dumbbell'],
        ];

        Amenity::insert($amenities);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
