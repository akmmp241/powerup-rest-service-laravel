<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\XenditWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    private XenditWebhookService $xenditWebhookService;

    public function __construct()
    {
        $this->xenditWebhookService = new XenditWebhookService();
    }

    public function channelStatus(Request $request): JsonResponse
    {
        $payload = $request->get("data");

        $this->xenditWebhookService->handleChannelStatus($payload);

        return $this->base(true, 200, "Success");
    }

    public function paymentSucceeded(Request $request): JsonResponse
    {
        $payload = $request->get("data");

        $this->xenditWebhookService->handleSucceeded($payload);

        return $this->base(true, 200, "Success");
    }

    public function paymentFailed(Request $request): JsonResponse
    {
        $payload = $request->get("data");

        $this->xenditWebhookService->handleFailed($payload);

        return $this->base(true, 200, "Success");
    }

    public function paymentPending(Request $request): JsonResponse
    {
        $payload = $request->get("data");

        $this->xenditWebhookService->handlePending($payload);

        return $this->base(true, 200, "Success");
    }
}
