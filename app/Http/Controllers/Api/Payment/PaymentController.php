<?php

namespace App\Http\Controllers\Api\Payment;

use App\Exceptions\ProductNotFoundException;
use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ChargeRequest;
use App\Models\Product;
use App\Services\Payment\PaymentServiceImplement;
use App\Xendit\Charge;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    use Charge;

    private PaymentServiceImplement $paymentServiceImplement;

    public function __construct()
    {
        $this->paymentServiceImplement = new PaymentServiceImplement();
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
        $this->paymentServiceImplement->setTransactionId($transactionId);

        // create transaction in database
        $transaction = $this->paymentServiceImplement->createTransaction($data);

        // create xendit request payload
        $payment_request_parameters = $this->paymentServiceImplement->chargeRequestPayload($transaction, $data["channel_code"]);

        // create charge in xendit
        $res = $this->chargeWithEwallet($payment_request_parameters);
        $payload = $res->json();

        // save transaction
        $transaction->status = $payload["status"];
        $transaction->payment_channel_status = $payload["payment_method"]["status"];
        $transaction->save();

        $responsePayload = [
            "transaction_id" => $transactionId,
            "product_name" => $transaction->product_name,
            "destination" => $transaction->destination,
            "server_id" => $transaction->server_id,
            "payment_method" => $transaction->payment_method,
            "total" => $transaction->total,
            "status" => $transaction->status,
            "created_at" => $transaction->created_at,
            "updated_at" => $transaction->updated_at
        ];

        return $this->baseWithData(
            true,
            ResponseCode::HTTP_CREATED,
            "Success create Transaction",
            $responsePayload
        );
    }
}
