<?php

namespace Tests\Feature\Products;

use App\Http\Resources\Products\Homepage\PromosCollection;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Database\Seeders\PromoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function testSuccessGetProductPromos()
    {
        $this->seed([PromoSeeder::class]);
        $user = User::factory()->create();
        $promos = Promo::query()->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/promos")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Promos",
                "data" => (new PromosCollection($promos))->jsonSerialize()
            ]);
    }

    public function testUnauthorizedGetPromos()
    {
        $this->get("/api/products/promos")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }

    public function testProductPromoRelation()
    {
        $this->seed([PromoSeeder::class]);
        $product = Product::query()->first();
        $promo = Promo::factory()->for($product)->create();

        self::assertInstanceOf(Promo::class, $product->promo);
    }
}
