<?php

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
        Schema::create('investment_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('property_id')->unique()->constrained('properties')->onDelete('cascade');
            $table->decimal('suggested_valuation', 15, 2)->nullable();
            $table->decimal('suggested_investment_amount', 15, 2)->nullable();
            $table->decimal('suggested_monthly_operating_cost', 10, 2)->nullable();
            $table->decimal('suggested_nightly_rent', 10, 2)->nullable();
            $table->decimal('owner_share', 5, 2)->default(0.00); // Proportion of the owner (e.g., 0.60 for 60%)
            $table->decimal('investor_share', 5, 2)->default(0.00); // Proportion of the investors
            $table->decimal('platform_fee_share', 5, 2)->default(0.00); // Proportion of the platform
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_offers');
    }
};
