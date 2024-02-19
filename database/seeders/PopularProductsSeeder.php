<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\PopularProducts;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PopularProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([ProductSeeder::class]);
        $operator = Operator::query()->first();
        PopularProducts::factory()->for($operator)->create();
    }
}
