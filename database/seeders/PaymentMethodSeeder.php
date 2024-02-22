<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(PaymentMethodCategorySeeder::class);

        PaymentMethod::query()->create([
            "type" => "EWALLET",
            "channel_code" => "DANA",
            "name" => "DANA",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethod::query()->create([
            "type" => "EWALLET",
            "channel_code" => "OVO",
            "name" => "OVO",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethod::query()->create([
            "type" => "OVER_THE_COUNTER",
            "channel_code" => "ALFAMART",
            "name" => "Alfamart",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethod::query()->create([
            "type" => "OVER_THE_COUNTER",
            "channel_code" => "INDOMARET",
            "name" => "Indomaret",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethod::query()->create([
            "type" => "VIRTUAL_ACCOUNT",
            "channel_code" => "BCA",
            "name" => "VA BCA",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);

        PaymentMethod::query()->create([
            "type" => "VIRTUAL_ACCOUNT",
            "channel_code" => "BNI",
            "name" => "VA BNI",
            "icon" => "https://icon.com/icon.png",
            "object" => "{\"success\": true}"
        ]);
    }
}
