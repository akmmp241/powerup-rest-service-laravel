<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->title(),
            "percentage" => fake()->numberBetween(10, 30),
            "description" => fake()->sentence(),
            "product_url" => fake()->url(),
            "image_url" => fake()->imageUrl(),

        ];
    }
}
