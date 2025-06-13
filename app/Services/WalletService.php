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

    public function withdraw(Wallet $wallet, float $amount, $source = null, string $description = ''): Transaction
    {
        if ($wallet->balance < $amount) {
            throw new \Exception("Insufficient balance");
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $description) {
            $wallet->decrement('balance', $amount);

            return $wallet->transactions()->create([
                'type' => 'withdrawal',
                'amount' => -$amount,
                'status' => 'completed',
                'description' => $description,
                'source_type' => optional($source)->getMorphClass(),
                'source_id' => optional($source)->id,
            ]);
        });
    }

    public function credit(Wallet $wallet, float $amount, $type = 'income_share', $source = null, string $description = ''): Transaction
    {
        return $this->deposit($wallet, $amount, $source, $description);
    }

    public function debit(Wallet $wallet, float $amount, $type = 'investment', $source = null, string $description = ''): Transaction
    {
        return DB::transaction(function () use ($wallet, $amount, $type, $source, $description) {
            if ($wallet->balance < $amount) {
                throw new \Exception("Insufficient balance");
            }

            $wallet->decrement('balance', $amount);

            return $wallet->transactions()->create([
                'type' => $type,
                'amount' => -$amount,
                'status' => 'completed',
                'description' => $description,
                'source_type' => optional($source)->getMorphClass(),
                'source_id' => optional($source)->id,
            ]);
        });
    }
}
