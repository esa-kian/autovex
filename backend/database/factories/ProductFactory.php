<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'sku' => strtoupper(Str::random(8)),
            'images' => json_encode([$this->faker->imageUrl()]),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
