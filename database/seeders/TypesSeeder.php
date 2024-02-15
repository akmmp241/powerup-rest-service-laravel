<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(OperatorSeeder::class);
        $operator = Operator::query()->first();
        Type::factory()->count(10)->for($operator)->create();
    }
}
