<?php

namespace App\Services\Payment;

use App\Exceptions\FailedCreateTransactionException;
use App\Models\TokovoucherProduct;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TokoVoucherChargeService
{
    private ?string $transactionId;

    private array $requestPayload;

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function chargeRequestPayload(TokovoucherProduct $product, Transaction|Model $transaction): void
    {
        $signature = env("TOKOVOUCHER_MEMBER_CODE") . ":" . env("TOKOVOUCHER_SECRET_KEY") . ":" . $this->transactionId;

        $this->requestPayload = [
            "ref_id" => $this->transactionId,
            "produk" => $product->code,
            "tujuan" => $transaction->destination,
            "server_id" => $transaction->server_id,
            "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
            "signature" => md5($signature)
        ];
    }

    public function createCharge(): Response
    {
        try {
            return Http::asJson()->timeout(15)
                ->retry(3, 500)
                ->accept("application/json")
                ->contentType("application/json")
                ->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                ->post("/v1/transaksi", $this->requestPayload)
                ->throw();
        } catch (RequestException $e) {
            Log::info($e->response->json());
            throw new FailedCreateTransactionException($e->response);
        }
    }

    public function getTransaction(): ?array
    {
        if (!$this->transactionId) {
            return null;
        }

        $memberCode = env("TOKOVOUCHER_MEMBER_CODE");
        try {
            $res = Http::asJson()->timeout(15)
                ->retry(3, 500)
                ->accept("application/json")
                ->contentType("application/json")
                ->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                ->post("/v1/transaksi/status", [
                    "ref_id" => $this->transactionId,
                    "member_code" => $memberCode,
                    "signature" => md5($memberCode . ":" . env("TOKOVOUCHER_SECRET_KEY") . ":" . $this->transactionId)
                ])
                ->throw();

            Log::info($res->json());

            return $res->json();
        } catch (RequestException $e) {
            Log::info($e->response->json());
            throw new FailedCreateTransactionException($e->response);
        }
    }

    public function updateIfChange(Transaction|Model $transaction, ?array $payload): void
    {
        if (!$payload) return;

        switch ($payload["status"]) {
            case "sukses":
                if ($transaction->status !== "SUCCEED") {
                    $transaction->status = "SUCCEED";
                    $transaction->save();
                }
                break;
            case "pending":
                if ($transaction->status !== "PROCESS") {
                    $transaction->status = "PROCESS";
                    $transaction->save();
                }
                break;
            case "gagal":
                Log::info($payload["sn"]);
                if ($transaction->status !== "FAILED") {
                    $transaction->status = "FAILED";
                    $transaction->failure_code = $payload["sn"];
                    $transaction->save();
                }
                break;
        }
    }
}
