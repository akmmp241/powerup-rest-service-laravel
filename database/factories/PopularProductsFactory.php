<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PopularProductsFactory extends Factory
{
    public function definition(): array
    {
        return [
            "title" => fake()->title(),
            "image" => fake()->imageUrl(),
            "link" => fake()->url(),
            "description" => fake()->sentence()
        ];
    }
}
