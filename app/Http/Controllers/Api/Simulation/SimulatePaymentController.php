<?php

namespace App\Http\Controllers\Api\Simulation;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Simulation\SimulateQRCodeRequest;
use App\Models\Transaction;
use App\Services\Payment\XenditChargeService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SimulatePaymentController extends Controller
{
    private XenditChargeService $xenditChargeService;

    public function __construct()
    {
        $this->xenditChargeService = new XenditChargeService();
    }

    public function simulate(SimulateQRCodeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data["amount"] = (int) $data["amount"];

        $transaction = Transaction::query()->where("id", $data["id"])->first();
        $this->xenditChargeService->setTransactionId($transaction->xendit_ref_id);
        $transaction = $this->xenditChargeService->getTransaction();

        try {
            Http::asJson()->timeout(15)->retry(3, 500)->withHeaders([
                "Authorization" => "Basic " . base64_encode(env('XENDIT_API_KEY') . ':'),
            ])->accept("application/json")
                ->contentType("application/json")
                ->baseUrl(config("xendit.base-url"))
                ->post("/v2/payment_methods/" . $transaction["payment_method"]["id"] . "/payments/simulate", ["amount" => $data["amount"]])
                ->throw();
        } catch (RequestException $e) {
            if ($e->response->status() === ResponseCode::HTTP_BAD_REQUEST && $e->response->json()["error_code"] === "INCORRECT_AMOUNT")
                throw new HttpResponseException($this->base(
                    success: false,
                    code: ResponseCode::HTTP_BAD_REQUEST,
                    message: "Incorrect Amount"
                ));

            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_INTERNAL_SERVER_ERROR,
                message: "Something Wrong"
            ));
        }

        return $this->base(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Simulate Payment"
        );
    }
}
