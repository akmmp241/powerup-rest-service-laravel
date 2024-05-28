<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Tokovoucher\TokoVoucher;
use App\Traits\Responses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class XenditWebhookService
{
    use TokoVoucher, Responses;

    private TokoVoucherChargeService $tokoVoucherChargeService;

    public function __construct()
    {
        $this->tokoVoucherChargeService = new TokoVoucherChargeService();
    }

    public function handleSucceeded(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->firstOrFail();
        $product = $this->getProduct($transaction->product_code);

        $this->tokoVoucherChargeService->setTransactionId($transaction->id);
        $this->tokoVoucherChargeService->chargeRequestPayload($product, $transaction);
        $res = $this->tokoVoucherChargeService->createCharge();

        if ($res->json()["status"] === "gagal") {
            Log::info(json_encode($res->collect(), JSON_PRETTY_PRINT));
            throw new HttpResponseException($this->base(
                success: false,
                code: 400,
                message: $res->json()["sn"]
            ));
        }

        if ($res->json()["status"] === 0) {
            Log::info(json_encode($res->collect(), JSON_PRETTY_PRINT));
            throw new HttpResponseException($this->base(
                success: false,
                code: 500,
                message: $res->json()["error_msg"]
            ));
        }

        if ($res->json()["status"] === "pending") $status = "PROCESS";

        $transaction->status = $status ?? "PAID";
        $transaction->save();
    }

    public function handleFailed(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->firstOrFail();

        $transaction->status = $payload["status"];
        $transaction->failure_code = $payload["failure_code"];
        $transaction->save();
    }

    public function handlePending(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->firstOrFail();

        $transaction->status = $payload["status"];
        $transaction->save();
    }

    public function handleChannelStatus(array $payload): void
    {
        $transactionId = last(explode('-', $payload["reference_id"]));

        $transaction = Transaction::query()->where("id", $transactionId)->firstOrFail();

        $transaction->payment_channel_status = $payload["status"];
        $transaction->save();
    }
}
