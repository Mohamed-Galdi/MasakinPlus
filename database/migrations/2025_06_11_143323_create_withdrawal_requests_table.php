<?php

use App\Enums\WithdrawRequestStatus;
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
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable(); // e.g., 'bank', 'stcpay'
            $table->json('details')->nullable(); // optional: IBAN, account name, etc.

            $table->enum('status', array_column(WithdrawRequestStatus::cases(), 'value'))->default(WithdrawRequestStatus::Pending->value);
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
