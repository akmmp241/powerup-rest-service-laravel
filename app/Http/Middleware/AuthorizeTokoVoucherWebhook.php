<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseCode;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeTokoVoucherWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
//        $token = env("TOKOVOUCHER_MEMBER_CODE") . ":" . env("TOKOVOUCHER_SECRET_KEY") . ":" . $request->get("ref_id");
//        Log::info("token: " . $token);
//        Log::info("callback: " . $request->header("X-TokoVoucher-Authorization"));
//
//        if ($request->header("X-TokoVoucher-Authorization") === md5($token)) {
//            Log::info("hai");
//            throw new HttpResponseException($this->base(
//                false,
//                ResponseCode::HTTP_UNAUTHORIZED,
//                "Invalid Callback Token"
//            ));
//        }

        return $next($request);
    }
}
