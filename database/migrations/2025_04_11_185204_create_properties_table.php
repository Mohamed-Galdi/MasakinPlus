<?php

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->enum('type', PropertyType::labels())->default(PropertyType::Apartment->label());
            $table->enum('status', array_column(PropertyStatus::cases(), 'value'))->default(PropertyStatus::Draft->value);
            $table->string('city');
            $table->string('address')->nullable();
            $table->float('area')->default(0); // Area in square meters
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('valuation', 12, 2)->default(0.00); // Price of the property
            $table->decimal('investment_required', 12, 2)->default(0.00); // Required investment amount
            $table->decimal('monthly_operating_cost', 10, 2)->default(0.00); // Monthly operating cost
            $table->decimal('nightly_rent', 10, 2)->nullable(); // Rent price per night
            $table->decimal('owner_share', 5, 2)->default(0.00); // Proportion of the owner (e.g., 0.60 for 60%)
            $table->decimal('investor_share', 5, 2)->default(0.00); // Proportion of the investors
            $table->decimal('platform_fee_share', 5, 2)->default(0.00); // Proportion of the platform
            $table->integer('expected_occupancy')->default(28); // Expected occupancy
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
