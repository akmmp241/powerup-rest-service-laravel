<?php

namespace Tests\Feature\Payment;

use App\Http\Resources\Payment\PaymentCategoriesCollection;
use App\Models\PaymentMethodCategory;
use App\Models\User;
use Database\Seeders\PaymentMethodSeeder;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PaymentPageTest extends TestCase
{
    public function testCastJsonToArray()
    {
        $this->seed(PaymentMethodSeeder::class);

        $paymentMethod = PaymentMethodCategory::query()->first();

        self::assertIsArray($paymentMethod->object);
    }

    public function testSuccessGetPaymentMethods()
    {
        $user = User::factory()->create();
        $this->seed([PaymentMethodSeeder::class]);
        $paymentMethods = PaymentMethodCategory::query()->with("payment_methods")->get();

        Log::info(json_encode((new PaymentCategoriesCollection($paymentMethods))->jsonSerialize(), JSON_PRETTY_PRINT));

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/payments/methods")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get All Payment Methods",
                "data" => (new PaymentCategoriesCollection($paymentMethods))->jsonSerialize()
            ]);
    }

    public function testUnauthorizedGetPaymentMethods()
    {
        $this->get("/api/payments/methods")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action",
            ]);
    }


}
