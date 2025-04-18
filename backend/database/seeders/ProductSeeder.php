<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Smartphone X',
                'sku' => 'PHONE-X-001',
                'images' => json_encode(['phone-x-front.jpg', 'phone-x-back.jpg']),
                'description' => 'The latest smartphone with amazing features and performance.',
                'price' => 999.99,
            ],
            [
                'title' => 'Wireless Headphones',
                'sku' => 'AUDIO-WH-002',
                'images' => json_encode(['headphones-1.jpg', 'headphones-2.jpg']),
                'description' => 'High-quality wireless headphones with noise cancellation.',
                'price' => 249.99,
            ],
            [
                'title' => 'Laptop Pro',
                'sku' => 'COMP-LP-003',
                'images' => json_encode(['laptop-pro.jpg']),
                'description' => 'Professional laptop for developers and designers.',
                'price' => 1499.99,
            ],
            [
                'title' => 'Smartwatch',
                'sku' => 'WATCH-SW-004',
                'images' => json_encode(['smartwatch-black.jpg', 'smartwatch-silver.jpg']),
                'description' => 'Track your fitness and stay connected with this smartwatch.',
                'price' => 349.99,
            ],
            [
                'title' => 'Wireless Earbuds',
                'sku' => 'AUDIO-WE-005',
                'images' => json_encode(['earbuds-case.jpg', 'earbuds-white.jpg']),
                'description' => 'Compact wireless earbuds with crystal clear sound.',
                'price' => 179.99,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}