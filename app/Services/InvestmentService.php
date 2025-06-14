<?php

namespace App\Services;

use App\Enums\InvestStatus;
use App\Enums\TxStatus;
use App\Enums\TxType;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    protected WalletService $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    public function createInvestment(User $user, string $propertyId, float $amount): Investment
    {
        return DB::transaction(function () use ($user, $propertyId, $amount) {
            $wallet = $user->wallet;

            if (!$wallet) {
                throw new \Exception('المحفضة غير جاهزة للإستخدام');
            }

            if ($wallet->balance < $amount) {
                throw new \Exception('رصيد المحفضة غير كافي لإتمام العملية');
            }

            // 1. Create investment record
            $investment = Investment::create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'property_id' => $propertyId,
                'amount' => $amount,
                'status' => InvestStatus::Active->value, // Or Pending, depending on logic
                'invested_at' => now(),
            ]);

            // 2. Create debit transaction and link it to the investment
            $wallet->transactions()->create([
                'type' => TxType::Investment->value, // Add this to your TxType enum
                'amount' => -$amount,
                'status' => TxStatus::Completed->value,
                'description' => 'إستثمار في العقار ',
                'transactionable_type' => Investment::class,
                'transactionable_id' => $investment->id,
            ]);

            // 3. Decrease wallet balance
            $wallet->decrement('balance', $amount);

            return $investment;
        });
    }
}
