<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class XenditWebhookService
{
    public function handleSucceeded(array $payload): void
    {
        $transaction = Transaction::query()->where("id", $payload["reference_id"])->firstOrFail();

        $transaction->status = $payload["status"];
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
