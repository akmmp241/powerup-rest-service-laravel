<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            "image" => fake()->imageUrl(),
            "description" => fake()->sentence()
        ];
    }
}
