<?php

namespace App\Services\Payment;

use App\Exceptions\ProductNotFoundException;
use App\Models\Transaction;
use App\Tokovoucher\TokoVoucher;
use App\Traits\Responses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Date;

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
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->first();

        if (!$transaction) throw new ProductNotFoundException();

        $product = $this->getProduct($transaction->product_code);

        $this->tokoVoucherChargeService->setTransactionId($transaction->id);
        $this->tokoVoucherChargeService->chargeRequestPayload($product, $transaction);
        $res = $this->tokoVoucherChargeService->createCharge();

        $payload = $res->json();

        if ($payload["status"] === "gagal") {
            throw new HttpResponseException($this->base(
                success: false,
                code: 400,
                message: $payload["sn"]
            ));
        }

        if ($payload["status"] === 0) {
            throw new HttpResponseException($this->base(
                success: false,
                code: 500,
                message: $payload["error_msg"]
            ));
        }

        if ($payload["status"] === "pending") $status = "PROCESS";

        $transaction->status = $status ?? "PAID";
        $transaction->tokovoucher_ref_id = $payload["trx_id"];
        $transaction->paid_at = Date::now()->format("Y-m-d H:i:s");
        $transaction->save();
    }

    public function handleFailed(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->first();

        if (!$transaction) throw new ProductNotFoundException();

        $transaction->status = $payload["status"];
        $transaction->failure_code = $payload["failure_code"];
        $transaction->save();
    }

    public function handlePending(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->first();

        if (!$transaction) throw new ProductNotFoundException();

        $transaction->status = $payload["status"];
        $transaction->save();
    }

    public function handleChannelStatus(array $payload): void
    {
        $transactionId = last(explode('-', $payload["reference_id"]));

        $transaction = Transaction::query()->where("id", $transactionId)->first();

        if (!$transaction) throw new ProductNotFoundException();

        $transaction->payment_channel_status = $payload["status"];
        $transaction->save();
    }
}
