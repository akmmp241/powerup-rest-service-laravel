<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(TypesSeeder::class);
        $type = Type::query()->first();
        Product::factory()->count(10)->for($type)->create();
    }
}