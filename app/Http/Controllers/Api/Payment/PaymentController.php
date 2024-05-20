<?php

namespace App\Http\Controllers\Api\Payment;

use App\Exceptions\ProductNotFoundException;
use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ChargeRequest;
use App\Models\Product;
use App\Services\Payment\XenditChargeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private XenditChargeService $xenditChargeService;

    public function __construct()
    {
        $this->xenditChargeService = new XenditChargeService();
    }

    public function charge(ChargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = Product::query()->find($data["product_id"]);

        if (is_null($product)) {
            throw new ProductNotFoundException();
        }

        $data["product_name"] = $product->name;
        $transactionId = Str::random(40);

        // set the transaction ID
        $this->xenditChargeService->setTransactionId($transactionId);

        // create transaction in database
        $transaction = $this->xenditChargeService->createTransaction($data);

        // set xendit request payload
        $this->xenditChargeService->setRequestPayload($transaction, $data["channel_code"]);

        // create charge in xendit
        $res = $this->xenditChargeService->createCharge();
        $payload = $res->json();

        // save transaction
        $transaction->status = $payload["status"];
        $transaction->payment_channel_status = $payload["payment_method"]["status"];
        $transaction->save();

        // load response payload
        $responsePayload = $this->xenditChargeService->createResponsePayload($transaction);

        return $this->baseWithData(
            true,
            ResponseCode::HTTP_CREATED,
            "Success create Transaction",
            $responsePayload
        );
    }
}
