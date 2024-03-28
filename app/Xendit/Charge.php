<?php

namespace App\Xendit;

use App\Exceptions\FailedCreateTransactionException;
use App\Traits\Responses;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait Charge
{
    use Responses;
    public function chargeWithEwallet(?array $data): Response
    {
        try {
            return Http::asJson()->timeout(15)->retry(3, 500)->withHeaders([
                "Authorization" => "Basic " . base64_encode(env('XENDIT_API_KEY') . ':'),
            ])->accept("application/json")
                ->contentType("application/json")
                ->baseUrl(config("xendit.base-url"))
                ->post("/payment_requests", $data)
                ->throw();
        } catch (RequestException $e) {
            Log::info($e->response->json());
            throw new FailedCreateTransactionException($e->response);
        }
    }
}
