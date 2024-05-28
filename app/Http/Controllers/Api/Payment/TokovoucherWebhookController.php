<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokovoucherWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        Log::info($request);

        $transaction = Transaction::query()->where("id", $request->get("ref_id"))->firstOrFail();

        if ($request->get("status") === "sukses") {
            $transaction->status = "SUCCEED";
        }

        if ($request->get("status") === "gagal") {
            $transaction->status = "FAILED";
            $transaction->failure_code = $request->get("sn");
        }

        $transaction->save();

        return $this->base(true, 200, "Success");
    }
}
