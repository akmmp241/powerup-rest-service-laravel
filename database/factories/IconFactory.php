<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IconFactory extends Factory
{
    public function definition(): array
    {
        return [
            "icon_url" => fake()->imageUrl()
        ];
    }
}
