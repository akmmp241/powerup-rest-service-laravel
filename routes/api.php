<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\Payment\PaymentPageController;
use App\Http\Controllers\Api\Products\HomepageController;
use App\Http\Controllers\Api\Products\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/register', [AuthenticationController::class, 'register']);
        Route::post('/login', [AuthenticationController::class, 'login']);

        Route::post('/forget-password', [AuthenticationController::class, 'sendForgetPassword']);
        Route::post('/reset-password', [AuthenticationController::class, 'getIdForResetPassword']);
        Route::patch('/reset-password', [AuthenticationController::class, 'resetPassword']);

    });

    Route::prefix("/products")->group(function () {
        Route::get('/home/banners', [HomepageController::class, 'getHomeBanners']);
        Route::get('/populars', [HomepageController::class, 'getPopularProducts']);
        Route::get('/promos', [HomepageController::class, 'getPromos']);
        Route::get('/categories', [ProductsController::class, 'getCategories']);
        Route::get('/operators', [ProductsController::class, 'getOperators']);
    });
});
Route::middleware('auth')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::get('/user', [AuthenticationController::class, 'user']);
        Route::get('/send-verification', [AuthenticationController::class, 'sendVerification']);
        Route::post('/verify', [AuthenticationController::class, 'verify']);
    });

    Route::prefix('/products')->group(function () {
        Route::get('/operators/{id}', [ProductsController::class, 'getSingleOperator']);
        Route::get('/types', [ProductsController::class, 'getTypes']);
        Route::get('', [ProductsController::class, 'getProducts']);
    });

    Route::prefix("/payments")->group(function () {
        Route::get("/methods", [PaymentPageController::class, 'getPaymentMethods']);
    });
});
