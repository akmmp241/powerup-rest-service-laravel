<?php

namespace App\Http\Controllers\Api\Payment;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ChargeRequest;
use App\Http\Resources\Payment\TransactionResource;
use App\Models\Operator;
use App\Models\Transaction;
use App\Services\Payment\TokoVoucherChargeService;
use App\Services\Payment\XenditChargeService;
use App\Tokovoucher\TokoVoucher;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    use TokoVoucher;

    private XenditChargeService $xenditChargeService;
    private TokoVoucherChargeService $tokoVoucherChargeService;

    public function __construct()
    {
        $this->xenditChargeService = new XenditChargeService();
        $this->tokoVoucherChargeService = new TokoVoucherChargeService();
    }

    public function charge(ChargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = $this->getProduct($data["product_code"]);

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
        $transaction->xendit_ref_id = $payload["id"];
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

    public function getTransaction(string $transactionId): JsonResponse
    {
        $transaction = Transaction::query()->where("id", $transactionId)->first();
        if (!$transaction) throw new HttpResponseException(
            $this->base(false, ResponseCode::HTTP_NOT_FOUND, "Transaction Not Found")
        );

        $product = $this->getProduct($transaction->product_code);

        $operator = Operator::query()->where("id", $transaction->operator_id)->first();
        if (!$operator) throw new HttpResponseException(
            $this->base(false, ResponseCode::HTTP_NOT_FOUND, "Operator Not Found")
        );

        $this->xenditChargeService->setTransactionId($transaction->xendit_ref_id);
        $transactionXendit = $this->xenditChargeService->getTransaction();
        $this->xenditChargeService->updateIfChange($transaction, $transactionXendit);

        $paymentPayload["ewallet"] = $transactionXendit["payment_method"]["ewallet"];
        $paymentPayload["virtual_account"] = $transactionXendit["payment_method"]["virtual_account"];
        $paymentPayload["qr_code"] = $transactionXendit["payment_method"]["qr_code"];
        $paymentPayload["over_the_counter"] = $transactionXendit["payment_method"]["over_the_counter"];

        $this->tokoVoucherChargeService->setTransactionId($transaction->id);
        $transactionTokoVoucher = $this->tokoVoucherChargeService->getTransaction();
        $this->tokoVoucherChargeService->updateIfChange($transaction, $transactionTokoVoucher);

        $transactionPayload = new TransactionResource($transaction);
        $transactionPayload->setProduct($product);
        $transactionPayload->setOperator($operator);

        $payload = [
            "transaction" => $transactionPayload,
            "payment" => $paymentPayload ?? null,
            "actions" => $transactionXendit["actions"][0] ?? null,
        ];

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success get Transaction",
            data: $payload
        );
    }
}
