<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => ' محمد كلدي',
            'email' => 'admin@masakinplus.com',
            'password' => '12345678',
            'type' => 'admin',
            'image' => '/storage/users_images/default-user-image.jpg',
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
