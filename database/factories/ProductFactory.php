<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'price' => fake()->randomFloat(2, 5000, 100000),
            'cost' => fake()->randomFloat(2, 2000, 50000),
            'stock' => fake()->numberBetween(0, 200),
            'image' => null,
            'is_active' => true,
        ];
    }
}
