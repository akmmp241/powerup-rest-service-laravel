<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ProductSeeder::class]);
        $product = Product::query()->first();
        Promo::factory()->for($product)->create();
    }
}
