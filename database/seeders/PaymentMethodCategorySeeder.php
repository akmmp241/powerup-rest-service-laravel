<?php

namespace Database\Seeders;

use App\Models\PaymentMethodCategory;
use Illuminate\Database\Seeder;

class PaymentMethodCategorySeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethodCategory::query()->create([
            "name" => "E-Wallet",
            "code" => "EWALLET",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethodCategory::query()->create([
            "name" => "Over The Counter",
            "code" => "OVER_THE_COUNTER",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethodCategory::query()->create([
            "name" => "Q-Ris",
            "code" => "QR_CODE",
            "object" => "{\"success\": true}"
        ]);
    }
}
