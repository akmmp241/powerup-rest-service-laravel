<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            "ref_id" => fake()->numberBetween(1, 100),
            "name" => fake()->name()
        ];
    }
}
