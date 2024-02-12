<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    use Responses;

    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::guest()) {
            return $next($request);
        }

        throw new HttpResponseException($this->base(
            success: false,
            code: ResponseCode::HTTP_UNAUTHORIZED,
            message: "You are not allowed to perform this action"
        ));
    }
}
