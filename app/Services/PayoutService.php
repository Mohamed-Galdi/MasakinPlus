<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Investment;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    protected WalletService $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    public function distributeIncome(Property $property, float $totalIncome)
    {
        $investments = $property->investments()->where('status', 'active')->get();
        $totalInvested = $investments->sum('amount');

        if ($totalInvested == 0) {
            return; // No investments, no payout
        }

        foreach ($investments as $investment) {
            $percentage = $investment->amount / $totalInvested;
            $share = $totalIncome * $percentage;

            $this->walletService->credit(
                $investment->wallet,
                $share,
                'income_share',
                null,
                "Rental income from property #{$property->id}"
            );
        }

        // You can also credit the owner, deduct platform fees, etc.
    }
}
