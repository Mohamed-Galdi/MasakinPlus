<?php

namespace Database\Seeders;

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
                    'type' => 'admin',
                    'image' => '/storage/users_images/default-user-image.webp',
                    'email_verified_at' => $date,
                ],
                [
                    'name' => ' محمد املاك',
                    'email' => 'owner@masakinplus.com',
                    'password' => $pw,
                    'type' => 'owner',
                    'image' => '/storage/users_images/default-user-image.webp',
                    'email_verified_at' => $date,
                ],
                [
                    'name' => ' محمد المستثمر',
                    'email' => 'investor@masakinplus.com',
                    'password' => $pw,
                    'type' => 'investor',
                    'image' => '/storage/users_images/default-user-image.webp',
                    'email_verified_at' => $date,
                ],
                [
                    'name' => ' محمد المستأجر',
                    'email' => 'tenant@masakinplus.com',
                    'password' => $pw,
                    'type' => 'tenant',
                    'image' => '/storage/users_images/default-user-image.webp',
                    'email_verified_at' => $date,
                ],
            ]
        );
    }
}
