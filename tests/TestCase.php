<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM users");
        DB::delete("DELETE FROM banners");
        DB::delete("DELETE FROM promos");
        DB::delete("DELETE FROM products");
        DB::delete("DELETE FROM types");
        DB::delete("DELETE FROM popular_products");
        DB::delete("DELETE FROM operators");
        DB::delete("DELETE FROM categories");
        DB::delete("DELETE FROM payment_methods");
        DB::delete("DELETE FROM payment_method_categories");
    }

    use CreatesApplication;


}
