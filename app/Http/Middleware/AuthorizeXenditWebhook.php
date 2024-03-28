<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeXenditWebhook
{
    use Responses;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header("x-callback-token") !== env("XENDIT_WEBHOOK_KEY")) {
            throw new HttpResponseException($this->base(
                false,
                ResponseCode::HTTP_UNAUTHORIZED,
                "Invalid Callback Token"
            ));
        }

        return $next($request);
    }
}
