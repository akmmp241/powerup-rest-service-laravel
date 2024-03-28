<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\PaymentMethod\EWalletChannelCode;
use Xendit\PaymentMethod\OverTheCounterChannelCode;
use Xendit\PaymentRequest\QRCodeChannelCode;
use Xendit\PaymentRequest\VirtualAccountChannelCode;

class PaymentServiceImplement
{
    private string $transactionId;

    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function createTransaction(array $data): Transaction
    {
        $transaction = new Transaction();
        $transaction->id = $this->transactionId;
        $transaction->user_id = Auth::check() ? Auth::id() : null;
        $transaction->mobile_number = $data["mobile_number"];
        $transaction->product_id = $data["product_id"];
        $transaction->product_name = $data["product_name"];
        $transaction->destination = $data["destination"];
        $transaction->server_id = $data["server_id"] ?? null;
        $transaction->payment_method = $data["channel_code"];
        $transaction->total = $data["total"];
        $transaction->status = strtoupper("pending");

        return $transaction;
    }

    public function chargeRequestPayload(Transaction $transaction, string $channelCode): array
    {
        $paymentMethod = $this->setPaymentMethod($channelCode, $transaction);

        return [
            "currency" => "IDR",
            "amount" => $transaction->total,
            "reference_id" => $this->transactionId,
            "checkout_method" => "ONE_TIME_PAYMENT",
            "channel_code" => $channelCode,
            "country" => "ID",
            "payment_method" => $paymentMethod
        ];
    }

    private function setPaymentMethod(string $channelCode, Transaction $transaction): ?array
    {
        // If Payment Method is Ewallet
        if (in_array($channelCode, VirtualAccountChannelCode::getAllowableEnumValues(), true)) {
            return $this->virtualAccountPayload($channelCode);
        }

        // If Payment Method is Virtual Account
        if (in_array($channelCode, EWalletChannelCode::getAllowableEnumValues(), true)) {
            return $this->ewalletPayload($channelCode, $transaction);
        }

        // if Payment Method is Qr
        if ($channelCode === QRCodeChannelCode::QRIS) {
            Log::info("this");
            return $this->qrPayload();
        }

        if (in_array($channelCode, OverTheCounterChannelCode::getAllowableEnumValues(), true)) {
            return $this->overTheCounterPayload($channelCode);
        }

        return null;
    }

    private function virtualAccountPayload(string $channelCode): array
    {
        return [
            "type" => "VIRTUAL_ACCOUNT",
            "reusability" => "ONE_TIME_USE",
            "reference_id" =>  "va-" . $this->transactionId,
            "virtual_account" => [
                "channel_code" => $channelCode,
                "channel_properties" => [
                    "customer_name" => Auth::check() ? Auth::user()->name : "You must login to set your name"
                ]
            ]
        ];
    }

    private function ewalletPayload(string $channelCode, Transaction $transaction): array
    {
        if ($channelCode === EWalletChannelCode::OVO) {
            $channelProperties = [
                "mobile_number" => $transaction->mobile_number
            ];
        } else {
            $channelProperties = [
                "success_return_url" => "https://example.com"
            ];
        }

        return [
            "type" => "EWALLET",
            "reusability" => "ONE_TIME_USE",
            "reference_id" =>  "ew-" . $this->transactionId,
            "ewallet" => [
                "channel_code" => $channelCode,
                "channel_properties" => $channelProperties
            ]
        ];
    }

    private function qrPayload(): array
    {
        return [
            "type" => "QR_CODE",
            "reusability" => "ONE_TIME_USE",
            "reference_id" =>  "qr-" . $this->transactionId,
        ];
    }

    private function overTheCounterPayload(string $channelCode): array
    {
        return [
            "type" => "OVER_THE_COUNTER",
            "reusability" => "ONE_TIME_USE",
            "reference_id" =>  "ro-" . $this->transactionId,
            "over_the_counter" => [
                "channel_code" => $channelCode,
                "channel_properties" => [
                    "customer_name" => "POWERUP"
                ]
            ]
        ];
    }
}
