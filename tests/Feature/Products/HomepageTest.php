<?php

namespace Tests\Feature\Products;

use App\Http\Resources\Products\Homepage\PromosCollection;
use App\Http\Resources\Products\PopularProductsCollection;
use App\Models\Operator;
use App\Models\PopularProducts;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Database\Seeders\PopularProductsSeeder;
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

    public function testSuccessGetPopularProducts()
    {
        $this->seed([PopularProductsSeeder::class]);
        $user = User::factory()->create();
        $populars = PopularProducts::query()->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get('/api/products/populars')
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Popular Products",
                "data" => (new PopularProductsCollection($populars))->jsonSerialize()
            ]);
    }

    public function testUnauthorizedGetPopularProducts()
    {
        $this->get("/api/products/populars")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }

    public function testOperatorPopularsRelation()
    {
        $this->seed([PopularProductsSeeder::class]);
        $operator = Operator::query()->first();
        PopularProducts::factory()->for($operator)->create();

        self::assertInstanceOf(PopularProducts::class, $operator->popular);
    }
}
