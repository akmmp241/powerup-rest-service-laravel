<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            "ref_id" => fake()->numberBetween(1, 100),
            "code" => fake()->languageCode(),
            "name" => fake()->name(),
            "description" => fake()->sentence()
        ];
    }
}
