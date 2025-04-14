<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->enum('type', array_column(PropertyType::cases(), 'value'))->default(PropertyType::Apartment->value);
            $table->enum('type_label', PropertyType::labels())->default(PropertyType::Apartment->label());
            $table->string('city');
            $table->string('address')->nullable();
            $table->float('area')->default(0); // Area in square meters
            $table->integer('bedrooms')->default(1); 
            $table->integer('bathrooms')->default(1);
            $table->boolean('is_furnished')->default(false); // مفروشة
            $table->decimal('daily_rent_price', 10, 2);
            $table->enum('status', array_column(PropertyStatus::cases(), 'value'))->default(PropertyStatus::Draft->value);
            $table->enum('status_label', PropertyStatus::labels())->default(PropertyStatus::Draft->label());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
