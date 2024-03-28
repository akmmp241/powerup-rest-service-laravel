<?php

namespace App\Exceptions;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class Handler extends ExceptionHandler
{
    use Responses;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (FailedValidationException $e, Request $request) {
            throw new HttpResponseException($this->baseWithError(
                code: ResponseCode::HTTP_BAD_REQUEST,
                message: "Bad Request",
                errors: $e->getValidator()->errors()
            ));
        });

        $this->renderable(function (ProductNotFoundException $e, Request $request) {
            throw new HttpResponseException($this->base(
                false,
                code: ResponseCode::HTTP_NOT_FOUND,
                message: "Product not found"
            ));
        });

        $this->renderable(function (FailedCreateTransactionException $e, Request $request) {
            throw new HttpResponseException($this->base(
                success: false,
                code: 500,
                message: $e->getResponse()->json()["message"]
            ));
        });
    }
}
