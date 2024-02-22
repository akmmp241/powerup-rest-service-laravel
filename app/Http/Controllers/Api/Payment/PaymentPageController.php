<?php

namespace App\Http\Controllers\Api\Payment;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentCategoriesCollection;
use App\Models\PaymentMethodCategory;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentPageController extends Controller
{
    use Responses;

    public function getPaymentMethods(): JsonResponse
    {
        $paymentMethods = PaymentMethodCategory::query()->with("payment_methods")->get();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get All Payment Methods",
            data: new PaymentCategoriesCollection($paymentMethods)
        );
    }
}
