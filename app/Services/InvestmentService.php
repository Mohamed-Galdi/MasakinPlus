<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Property;
use App\Models\Investment;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    protected WalletService $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    public function createInvestment(Wallet $wallet, Property $property, float $amount): Investment
    {
        return DB::transaction(function () use ($wallet, $property, $amount) {
            if ($wallet->balance < $amount) {
                throw new \Exception("Insufficient funds");
            }

            $investment = Investment::create([
                'wallet_id' => $wallet->id,
                'property_id' => $property->id,
                'amount' => $amount,
                'status' => 'active',
                'invested_at' => now(),
            ]);

            $this->walletService->debit(
                $wallet,
                $amount,
                'investment',
                $investment,
                "Investment in property #{$property->id}"
            );

            return $investment;
        });
    }
}
