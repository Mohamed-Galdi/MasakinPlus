<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arabicFirstNames = [
            'محمد',
            'أحمد',
            'عبدالله',
            'علي',
            'عمر',
            'خالد',
            'سعد',
            'فهد',
            'يوسف',
            'إبراهيم',
            'عبدالرحمن',
            'فاطمة',
            'عائشة',
            'مريم',
            'سارة',
            'نورة',
            'منى',
            'ليلى',
            'هدى',
            'رنا',
            'سلمى',
            'لينا',
            'دانة',
        ];

        $arabicLastNames = [
            'الشمري',
            'العتيبي',
            'القحطاني',
            'الدوسري',
            'المطيري',
            'الحربي',
            'الغامدي',
            'الزهراني',
            'العنزي',
            'السبيعي',
            'المالكي',
            'السلمي',
            'البلوي',
            'الظفيري',
            'الرشيدي',
            'الشهراني',
            'الجهني',
            'البقمي',
        ];

        $userTypes = [
            UserType::Owner->value,
            UserType::Investor->value,
            UserType::Tenant->value,
        ];

        $hashedPassword = Hash::make('password123');
        $batchSize = 10;
        $users = [];
        $allEmails = []; // Track all emails for wallet creation

        foreach ($userTypes as $type) {
            for ($i = 0; $i < 12; $i++) {
                $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
                $lastName = $arabicLastNames[array_rand($arabicLastNames)];
                $fullName = $firstName . ' ' . $lastName;

                // Create unique email based on name and a random number
                $emailName = Str::slug($firstName . $lastName . rand(100, 999), '');
                $email = $emailName . '@example.com';

                $createdAt = Carbon::now()->subDays(rand(1, 365));

                $userData = [
                    'name' => $fullName,
                    'email' => $email,
                    'email_verified_at' => Carbon::now(),
                    'password' => $hashedPassword,
                    'type' => $type,
                    'remember_token' => Str::random(10),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                $users[] = $userData;
                $allEmails[] = $email; // Store email for later wallet creation

                // Insert in batches to improve performance
                if (count($users) >= $batchSize) {
                    User::insert($users);
                    $users = [];
                }
            }
        }

        // Insert any remaining users
        if (count($users) > 0) {
            User::insert($users);
        }

        // Now create wallets for all the newly inserted users
        $newUsers = User::whereIn('email', $allEmails)->get();

        $wallets = $newUsers->map(function ($user) {
            return [
                'user_id' => $user->id,
                'balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Bulk insert wallets
        if (!empty($wallets)) {
            Wallet::insert($wallets);
        }
    }
}
