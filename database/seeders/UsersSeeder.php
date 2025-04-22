<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            'دانة'
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
            'البقمي'
        ];

        $userTypes = [
            UserType::Owner->value,
            UserType::Investor->value,
            UserType::Tenant->value,
        ];

        $hashedPassword = Hash::make('password123');
        $batchSize = 10;
        $users = [];

        foreach ($userTypes as $type) {
            for ($i = 0; $i < 12; $i++) {
                $firstName = $arabicFirstNames[array_rand($arabicFirstNames)];
                $lastName = $arabicLastNames[array_rand($arabicLastNames)];
                $fullName = $firstName . ' ' . $lastName;

                // Create unique email based on name and a random number
                $emailName = Str::slug($firstName . $lastName . rand(100, 999), '');
                $email = $emailName . '@example.com';

                $createdAt = Carbon::now()->subDays(rand(1, 365));

                $users[] = [
                    'name' => $fullName,
                    'email' => $email,
                    'email_verified_at' => Carbon::now(),
                    'password' => $hashedPassword,
                    'type' => $type,
                    'is_active' => (bool)rand(0, 1),
                    'remember_token' => Str::random(10),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                // Insert in batches to improve performance
                if (count($users) >= $batchSize) {
                    DB::table('users')->insert($users);
                    $users = [];
                }
            }
        }

        // Insert any remaining users
        if (count($users) > 0) {
            DB::table('users')->insert($users);
        }
    }
}
