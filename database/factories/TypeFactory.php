<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        return [
            "ref_id" => fake()->numberBetween(1, 100),
            "name" => fake()->name(),
            "format_form" => json_encode(["test" => "test"])
        ];
    }
}
