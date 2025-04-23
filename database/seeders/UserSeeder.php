<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pw = Hash::make('eVdVItth9pxxMTE');
        $date = now();

        User::insert(
            [
                [
                    'name' => ' محمد كلدي',
                    'email' => 'admin@masakinplus.com',
                    'password' => $pw,
                    'type' => UserType::Admin->value,
                    'email_verified_at' => $date,
                    'created_at' => $date,
                ],
                [
                    'name' => ' محمد املاك',
                    'email' => 'owner@masakinplus.com',
                    'password' => $pw,
                    'type' => UserType::Owner->value,
                    'email_verified_at' => $date,
                    'created_at' => $date,
                ],
                [
                    'name' => ' محمد المستثمر',
                    'email' => 'investor@masakinplus.com',
                    'password' => $pw,
                    'type' => UserType::Investor->value,
                    'email_verified_at' => $date,
                    'created_at' => $date,
                ],
                [
                    'name' => ' محمد المستأجر',
                    'email' => 'tenant@masakinplus.com',
                    'password' => $pw,
                    'type' => UserType::Tenant->value,
                    'email_verified_at' => $date,
                    'created_at' => $date,
                ],
            ]
        );
    }
}
