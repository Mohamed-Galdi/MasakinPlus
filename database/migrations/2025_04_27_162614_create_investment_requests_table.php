<?php

use App\Enums\InvestmentRequestStatus;
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
        Schema::create('investment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('property_id')->constrained('properties')->onDelete('cascade');
            $table->decimal('suggested_investment_amount', 15, 2);
            $table->decimal('suggested_daily_rent_price', 10, 2);
            $table->text('owner_note')->nullable();
            $table->enum('status', array_column(InvestmentRequestStatus::cases(), 'value'))->default(InvestmentRequestStatus::Pending->value);
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_requests');
    }
};
