<?php

namespace App\Services;

use App\Enums\DepositStatus;
use App\Enums\TxStatus;
use App\Enums\TxType;
use App\Models\Deposit;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function deposit(Wallet $wallet, float $amount, array $depositData = [], string $description = ''): Transaction
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        return DB::transaction(function () use ($wallet, $amount, $depositData, $description) {
            // 1. Create the deposit record
            $deposit = $wallet->deposits()->create(array_merge([
                'amount' => $amount,
                'status' => DepositStatus::Succeeded->value, // Since we only call this for successful payments
            ], $depositData));

            // 2. Create the transaction and link it to the deposit
            $transaction = $wallet->transactions()->create([
                'type' => TxType::Deposit->value,
                'amount' => $amount,
                'status' => TxStatus::Completed->value,
                'description' => $description,
                'transactionable_type' => Deposit::class,
                'transactionable_id' => $deposit->id,
            ]);

            // 3. Update wallet balance
            $wallet->increment('balance', $amount);

            return $transaction;
        });
    }

    public function withdraw()
    {
    }

    public function credit()
    {
    }

    public function debit()
    {
    }
}
