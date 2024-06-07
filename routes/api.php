<?php

use App\Helpers\ResponseCode;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Payment\PaymentPageController;
use App\Http\Controllers\Api\Payment\TokovoucherWebhookController;
use App\Http\Controllers\Api\Payment\XenditWebhookController;
use App\Http\Controllers\Api\Products\HomepageController;
use App\Http\Controllers\Api\Products\ProductsController;
use App\Http\Controllers\Api\Simulation\SimulatePaymentController;
use App\Http\Controllers\Api\Transaction\TransactionController;
use App\Http\Middleware\AuthorizeTokoVoucherWebhook;
use App\Http\Middleware\AuthorizeXenditWebhook;
use App\Http\Middleware\IsAuthorizeUserMiddleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

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

Route::fallback(function () {
    throw new HttpResponseException(Response::json([
        "success" => false,
        "status_code" => ResponseCode::HTTP_NOT_FOUND,
        "message" => "Route Not Found"
    ])->setStatusCode(ResponseCode::HTTP_NOT_FOUND));
});

Route::get('/payments/methods', [PaymentPageController::class, 'getPaymentMethods']);

Route::prefix('/products')->group(function () {
    Route::controller(HomepageController::class)->group(function () {
        Route::get('/home/banners', 'getHomeBanners');
        Route::get('/populars', 'getPopularProducts');
        Route::get('/promos', 'getPromos');
    });
    Route::controller(ProductsController::class)->group(function () {
        Route::get('/categories', 'getCategories');
        Route::get('/operators', 'getOperators');
        Route::get('/operators/{id}', 'getSingleOperator');
        Route::get('/types', 'getTypes');
        Route::get('', 'getProducts');
    });
});

Route::prefix("/transaction")->group(function () {
    Route::middleware([AuthorizeXenditWebhook::class])->controller(XenditWebhookController::class)->group(function () {
        Route::post("/success", 'paymentSucceeded');
        Route::post("/failed", 'paymentFailed');
        Route::post("/pending", 'paymentPending');
        Route::post('/channel-status', 'channelStatus');
    });

    Route::post("/status", [TokovoucherWebhookController::class, 'handle'])->middleware([AuthorizeTokoVoucherWebhook::class]);

    Route::get('/simulate', [SimulatePaymentController::class, 'simulate']);

    Route::controller(PaymentController::class)->group(function () {
        Route::post("/charge", 'charge');
        Route::get("/{transactionId}", "getTransaction");
    });

    Route::post('', [TransactionController::class, 'getTransactions']);
});

Route::controller(AuthenticationController::class)->prefix('/auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/forget-password', 'sendForgetPassword');
        Route::post('/reset-password', 'getIdForResetPassword');
        Route::patch('/reset-password', 'resetPassword');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/user', 'user');
        Route::get('/send-verification', 'sendVerification');
        Route::post('/verify', 'verify');
    });
});

//Route::get('/scrap', [ProductsController::class, 'scrapOperator']);
//Route::post('/scrap', [ProductsController::class, 'scrapTypes']);
