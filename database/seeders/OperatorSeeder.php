<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Operator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::factory()->create();
        Operator::factory()->count(10)->state([
            "category_id" => $category->id
        ])->create();
    }
}
