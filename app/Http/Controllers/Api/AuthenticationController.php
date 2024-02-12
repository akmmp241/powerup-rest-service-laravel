<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\ForgetPasswordRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Http\Requests\Authentication\UserIdForResetPasswordRequest;
use App\Http\Requests\Authentication\UserLoginRequest;
use App\Http\Requests\Authentication\UserRegisterRequest;
use App\Http\Requests\Authentication\VerifyEmailRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Jobs\SendMailJob;
use App\Mail\VerifyEmail;
use App\Models\ResetPassword;
use App\Models\User;
use App\Traits\Responses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    use Responses;

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->where('email', $data['email'])->first();

        if (!is_null($user)) {
            return $this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_ACCEPTABLE,
                message: "Email already registered"
            );
        }

        $user = new User($data);
        $user->token = Str::uuid();
        $user->save();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_CREATED,
            message: "Success Register User",
            data: new TokenResource($user)
        );
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $isSuccessLogin = Auth::attempt($data);

        if (!$isSuccessLogin) {
            return $this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_ACCEPTABLE,
                message: "Kombinasi Email dan Sandi tidak tepat"
            );
        }

        $user = User::query()->where('email', $data['email'])->first();
        $user->token = Str::uuid();
        $user->save();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Login",
            data: new TokenResource($user)
        );
    }

    public function user(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success get data user",
            data: new UserResource(Auth::user()
            ));
    }

    public function sendVerification(): JsonResponse
    {
        $user = Auth::user();

        if (RateLimiter::tooManyAttempts('sendVerification:' . $user->id, 1)) {
            $seconds = RateLimiter::availableIn('sendVerification:' . $user->id);
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_TOO_MANY_REQUESTS,
                message: "You can send new verification in $seconds seconds"
            ));
        }

        if (!is_null($user->verification_token) && !is_null($user->email_verified_at)) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_ACCEPTABLE,
                message: "Email already verified"
            ));
        }

        $random = Str::random(40);
        $domain = URL::to('/');
        $url = $domain . '/verify-email?verification_token=' . $random;

        $data = [
            "url" => $url,
            "email" => $user->email,
            "title" => "Email Verification",
            "body" => "Please click below here to verify your email"
        ];

        Mail::to($data['email'])->send(new VerifyEmail($data));

        $user->verification_token = $random;
        $user->save();

        RateLimiter::hit('sendVerification:' . $user->id);

        return $this->base(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Mail sent successfully"
        );
    }

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::query()
            ->where('verification_token', $data['token'])
            ->first();

        if (is_null($user)) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_FOUND,
                message: "Invalid Token"
            ));
        }

        if (!is_null($user->email_verified_at)) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_ACCEPTABLE,
                message: "Email already verified"
            ));
        }

        $user->email_verified_at = now();
        $user->save();

        return $this->base(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Email Verification Success"
        );
    }

    public function sendForgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (RateLimiter::tooManyAttempts('sendForgetPassword:' . $data['email'], 1)) {
            $seconds = RateLimiter::availableIn('sendForgetPassword:' . $data['email']);
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_TOO_MANY_REQUESTS,
                message: "You can send new verification in $seconds seconds"
            ));
        }

        $token = Str::random(40);
        $url = Env::get('FE_URL') . '/reset-password?reset_password_token=' . $token;

        $data = [
            "url" => $url,
            "email" => $data['email'],
            "title" => "Reset Password",
            "body" => "Please click below here to reset your password"
        ];

        dispatch(new SendMailJob($data));

        ResetPassword::query()->updateOrCreate([
            "email" => $data['email']
        ], [
            "email" => $data['email'],
            "token" => $token,
            "created_at" => now()
        ]);

        RateLimiter::hit('sendForgetPassword:' . $data['email']);

        return $this->base(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Email Sent Successfully"
        );
    }

    public function getIdForResetPassword(UserIdForResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $token = ResetPassword::query()->where('token', $data['token'])->first();

        if (is_null($token)) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_FOUND,
                message: "Invalid Token"
            ));
        }

        $id = User::query()->select('uuid')->where('email', $token->email)->first()->uuid;

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Token Valid",
            data: compact('id')
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->where('uuid', $data['id'])->first();

        if (is_null($user)) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_FOUND,
                message: "Invalid Id User"
            ));
        }

        $user->password = $data['password'];
        $user->save();

        $resetPassword = ResetPassword::query()->where('email', $user->email)->first();
        $resetPassword->token = null;
        $resetPassword->save();

        return $this->base(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success reset user password"
        );
    }
}
