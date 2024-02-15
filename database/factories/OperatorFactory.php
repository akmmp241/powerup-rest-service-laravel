<?php

namespace Database\Factories;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperatorFactory extends Factory
{
    protected $model = Operator::class;
    public function definition(): array
    {
        return [
            "ref_id" => fake()->numberBetween(0, 100),
            "name" => fake()->name(),
            "image" => fake()->imageUrl()
        ];
    }
}
