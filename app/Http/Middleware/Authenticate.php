<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    use Responses;

    public function handle(Request $request, Closure $next, string ...$guards)
    {
        if (Auth::check()) {
            return $next($request);
        }

        // Failed Authorization
        throw new HttpResponseException($this->base(
            success: false,
            code: ResponseCode::HTTP_UNAUTHORIZED,
            message: "You are not allowed to perform this action"
        ));
    }
}
