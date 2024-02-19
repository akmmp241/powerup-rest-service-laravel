<?php

namespace Tests\Feature\Products;

use App\Http\Resources\Products\CategoriesCollection;
use App\Http\Resources\Products\OperatorsCollection;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\TypesCollection;
use App\Models\Category;
use App\Models\Operator;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\OperatorSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TypesSeeder;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    public function testSuccessGetCategories()
    {
        $user = User::factory()->create();
        $this->seed([CategorySeeder::class]);
        $categories = Category::query()->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/categories")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Product Categories",
                "data" => (new CategoriesCollection($categories))->jsonSerialize()
            ]);
    }

    public function testUnauthorizedGetCategories()
    {
        $this->get("/api/products/categories")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }

    public function testSuccessGetOperators()
    {
        $user = User::factory()->create();
        $this->seed([OperatorSeeder::class]);
        $category = Category::query()->first();
        $operators = Operator::query()->where("category_id", $category->id)->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/operators?category_id=$category->id")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Product Operators",
                "data" => (new OperatorsCollection($operators))->jsonSerialize()
            ]);
    }

    public function testGetOperatorsBadRequest()
    {
        $user = User::factory()->create();
        $this->seed([OperatorSeeder::class]);
        $category = Category::query()->first();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/operators")
            ->assertStatus(400)
            ->assertJson([
                "success" => false,
                "status_code" => 400,
                "message" => "Bad Request"
            ]);
    }

    public function testUnauthorizedGetOperators()
    {
        $this->get("/api/products/operators")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }

    public function testSuccessGetType()
    {
        $user = User::factory()->create();
        $this->seed([TypesSeeder::class]);
        $operator = Operator::query()->first();
        $types = Type::query()->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/types?operator_id=$operator->id")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Product Types",
                "data" => (new TypesCollection($types))->jsonSerialize()
            ]);
    }

    public function testGetTypesBadRequest()
    {
        $user = User::factory()->create();
        $this->seed([TypesSeeder::class]);

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products/types")
            ->assertStatus(400)
            ->assertJson([
                "success" => false,
                "status_code" => 400,
                "message" => "Bad Request"
            ]);
    }

    public function testUnauthorizedGetTypes()
    {
        $this->get("/api/products/types")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }

    public function testSuccessGetProducts()
    {
        $user = User::factory()->create();
        $this->seed([ProductSeeder::class]);
        $type = Type::query()->first();
        $products = Product::query()->get();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products?type_id=$type->id")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "status_code" => 200,
                "message" => "Success Get Products",
                "data" => (new ProductCollection($products))->jsonSerialize()
            ]);
    }

    public function testGetProductsBadRequest()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withHeaders([
                "POWERUP-API-KEY" => $user->token
            ])
            ->get("/api/products")
            ->assertStatus(400)
            ->assertJson([
                "success" => false,
                "status_code" => 400,
                "message" => "Bad Request"
            ]);
    }

    public function testUnauthorizedGetProducts()
    {
        $this->get("/api/products")
            ->assertStatus(401)
            ->assertJson([
                "success" => false,
                "status_code" => 401,
                "message" => "You are not allowed to perform this action"
            ]);
    }
}
