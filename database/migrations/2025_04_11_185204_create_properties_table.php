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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->enum('type', ['شقة', 'منزل', 'فيلا', 'استوديو', 'مكتب'])->default('شقة');
            $table->string('city');
            $table->string('address')->nullable();
            $table->float('area')->default(0); // Area in square meters
            $table->integer('bedrooms')->default(1); 
            $table->integer('bathrooms')->default(1);
            $table->boolean('is_furnished')->default(false); // مفروشة
            $table->decimal('daily_rent_price', 10, 2);
            $table->decimal('monthly_rent_price', 10, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'funding', 'rented', 'inactive'])->default('pending');
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
