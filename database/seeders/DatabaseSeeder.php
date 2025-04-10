<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $pw = Hash::make('12345678');
        $date = now();

        User::insert([
            [
                'name' => ' محمد كلدي',
                'email' => 'admin@masakinplus.com',
                'password' => $pw,
                'type' => 'admin',
                'image' => '/storage/users_images/default-user-image.jpg',
                'email_verified_at' => $date,
            ],
            [
                'name' => ' محمد مالك',
                'email' => 'owner@masakinplus.com',
                'password' => $pw,
                'type' => 'owner',
                'image' => '/storage/users_images/default-user-image.jpg',
                'email_verified_at' => $date,
            ],
            [
                'name' => ' محمد المستثمر',
                'email' => 'investor@masakinplus.com',
                'password' => $pw,
                'type' => 'investor',
                'image' => '/storage/users_images/default-user-image.jpg',
                'email_verified_at' => $date,
            ],
            [
                'name' => ' محمد المستأجر',
                'email' => 'tenant@masakinplus.com',
                'password' => $pw,
                'type' => 'tenant',
                'image' => '/storage/users_images/default-user-image.jpg',
                'email_verified_at' => $date,
            ],
        ]);

        $response = Http::get('https://fakestoreapi.com/products');
        $products = $response->json();

        foreach ($products as $product) {
            Product::create([
                'name' => $product['title'],
                'price' => $product['price'],
                'image' => $product['image'],
                'status' => fake()->randomElement(['available', 'outofstock']),
            ]);
        }
    }
}
