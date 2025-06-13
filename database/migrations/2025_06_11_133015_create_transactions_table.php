<?php

use App\Enums\TxStatus;
use App\Enums\TxType;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', array_column(TxType::cases(), 'value'));
            $table->decimal('amount', 15, 2); // positive = credit, negative = debit
            $table->enum('status', array_column(TxStatus::cases(), 'value'));
            $table->morphs('transactionable');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
