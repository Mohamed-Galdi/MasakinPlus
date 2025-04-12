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
            $table->timestamps();
        });

        // Seed default Arabic amenities
        $amenities = [
            ['name' => 'واي فاي'],
            ['name' => 'موقف سيارات'],
            ['name' => 'مكيفات'],
            ['name' => 'حديقة'],
            ['name' => 'مسبح'],
            ['name' => 'صالة رياضية'],
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
