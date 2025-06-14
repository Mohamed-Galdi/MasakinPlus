<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Wallet;
use App\Services\WalletService;
use App\Services\InvestmentService;
use App\Enums\PropertyStatus;
use App\Enums\UserType;
use App\Enums\DepositStatus;

class InvestmentSeeder extends Seeder
{
    protected WalletService $walletService;
    protected InvestmentService $investmentService;

    public function __construct()
    {
        $this->walletService = new WalletService();
        $this->investmentService = new InvestmentService();
    }

    public function run(): void
    {
        // Get properties that are open for investment
        $properties = Property::where('status', PropertyStatus::OpenForInvestment)->get();

        if ($properties->isEmpty()) {
            return;
        }

        // Handle user with ID 3
        $this->handleSpecificUser();

        // Handle other investor users
        $this->handleInvestorUsers($properties);
    }

    private function handleSpecificUser(): void
    {
        // Eager load the wallet relationship
        $user = User::with('wallet')->find(3);
        if (!$user) {
            return;
        }

        // Ensure user has a wallet
        $wallet = $user->wallet ?? $user->wallet()->create(['balance' => 0]);

        // Create 2-3 larger deposits for user ID 3 (total 60,000-100,000)
        $numDeposits = fake()->numberBetween(2, 3);
        $totalDeposited = 0;

        for ($i = 0; $i < $numDeposits; $i++) {
            $depositAmount = fake()->numberBetween(20000, 40000); // Much larger deposits
            $totalDeposited += $depositAmount;

            $this->walletService->deposit(
                $wallet,
                $depositAmount,
                [
                    'gateway_reference' => 'PAY_' . fake()->unique()->numerify('########'),
                    'status' => DepositStatus::Succeeded->value,
                    'payment_gateway' => 'moyasar',
                ],
                'إيداع في المحفظة عبر بطاقة بنكية'
            );
        }

        // Make total balance between 60,000-100,000
        $targetBalance = fake()->numberBetween(60000, 100000);
        if ($totalDeposited < $targetBalance) {
            $additionalAmount = $targetBalance - $totalDeposited;
            $this->walletService->deposit(
                $wallet,
                $additionalAmount,
                [
                    'gateway_reference' => 'PAY_' . fake()->unique()->numerify('########'),
                    'status' => DepositStatus::Succeeded->value,
                    'payment_gateway' => 'moyasar',
                ],
                'إيداع إضافي في المحفظة'
            );
        }

        // Refresh wallet to get updated balance
        $wallet->refresh();

        // Create investments for user ID 3
        $this->createInvestmentsForUser($user, $wallet);
    }

    private function handleInvestorUsers($properties): void
    {
        // Get users with investor type (excluding user ID 3) and eager load wallets
        $investors = User::with('wallet')
            ->where('type', UserType::Investor->value)
            ->where('id', '!=', 3)
            ->get();

        if ($investors->isEmpty()) {
            return;
        }

        foreach ($investors as $investor) {
            // Ensure each investor has a wallet
            $wallet = $investor->wallet ?? $investor->wallet()->create(['balance' => 0]);

            // Create 1-3 larger deposits for each investor (total 30,000-80,000)
            $numDeposits = fake()->numberBetween(1, 3);
            $totalBalance = 0;

            for ($i = 0; $i < $numDeposits; $i++) {
                $depositAmount = fake()->numberBetween(15000, 35000); // Much larger deposits
                $totalBalance += $depositAmount;

                $this->walletService->deposit(
                    $wallet,
                    $depositAmount,
                    [
                        'gateway_reference' => 'PAY_' . fake()->unique()->numerify('########'),
                        'status' => DepositStatus::Succeeded->value,
                        'payment_gateway' => 'moyasar',
                    ],
                    'إيداع في المحفظة عبر بطاقة بنكية'
                );
            }

            // Refresh wallet to get updated balance
            $wallet->refresh();

            // Create investments for this investor
            $this->createInvestmentsForUser($investor, $wallet);
        }
    }

    private function createInvestmentsForUser(User $user, Wallet $wallet): void
    {
        $properties = Property::where('status', PropertyStatus::OpenForInvestment)->get();

        if ($properties->isEmpty()) {
            return;
        }

        // Determine how many investments this user should make (1-4)
        $numInvestments = fake()->numberBetween(1, min(4, $properties->count()));

        // Select random properties for investment
        $selectedProperties = $properties->random($numInvestments);

        $currentBalance = $wallet->balance;
        $remainingBalance = $currentBalance;

        foreach ($selectedProperties as $property) {
            // Skip if we don't have enough balance for meaningful investment
            if ($remainingBalance < 5000) {
                break;
            }

            // Calculate investment amount based on property's investment_required
            // Investment should be between 5,000 and 30% of required investment
            $maxInvestment = min(
                $remainingBalance * 0.6, // Don't invest more than 60% of remaining balance
                $property->investment_required * 0.3, // Up to 30% of property requirement
                50000 // Maximum single investment
            );

            $minInvestment = max(5000, $property->investment_required * 0.05); // At least 5% of property requirement or 5000

            if ($maxInvestment < $minInvestment) {
                continue; // Skip this property if we can't make a meaningful investment
            }

            // Round to nearest 1000
            $investmentAmount = round(fake()->numberBetween($minInvestment, $maxInvestment) / 1000) * 1000;

            try {
                $investment = $this->investmentService->createInvestment(
                    user: $user,
                    propertyId: $property->id,
                    amount: $investmentAmount
                );

                $remainingBalance -= $investmentAmount;

                // Refresh wallet balance
                $wallet->refresh();
            } catch (\Exception $e) {
                break; // Stop creating more investments for this user if one fails
            }
        }
    }
}
