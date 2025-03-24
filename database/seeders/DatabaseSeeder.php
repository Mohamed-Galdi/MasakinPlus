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
            'name' => 'Prime Vilt',
            'email' => 'primevilt@gmail.com',
            'type' => 'admin',
            'image' => '/storage/users_images/takamura.jpg'
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
