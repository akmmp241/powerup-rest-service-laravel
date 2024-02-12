<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;

trait Responses
{

    public function base(bool $success, int $code, string $message): JsonResponse
    {
        return Response::json([
            "success" => $success,
            "status_code" => $code,
            "message" => $message
        ])->setStatusCode($code);
    }

    public function baseWithData(bool $success, int $code, string $message, $data): JsonResponse
    {
        return Response::json([
            "success" => $success,
            "status_code" => $code,
            "message" => $message,
            "data" => $data
        ])->setStatusCode($code);
    }

    public function baseWithError(bool $success, int $code, string $message, MessageBag $errors): JsonResponse
    {
        return Response::json([
            "success" => $success,
            "status_code" => $code,
            "message" => $message,
            "errors" => $errors
        ])->setStatusCode($code);
    }
}
