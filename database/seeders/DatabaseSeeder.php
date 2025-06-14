<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UsersSeeder::class,
            OwnerPropertiesSeeder::class,
            InvestorPropertiesSeeder::class,
            TicketSeeder::class,
            InvestmentRequestSeeder::class,
            InvestmentSeeder::class,
            HomePageSeeder::class,
            AboutPageSeeder::class,
            PrivacyPageSeeder::class,
        ]);
    }
}
