<?php

namespace App\Http\Requests\Authentication;

use App\Exceptions\FailedValidationException;
use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return Auth::guest();
    }

    public function rules(): array
    {
        return [
            "email" => ["required", "max:255", "email"],
            "name" => ["required", "max:255", "min:1"],
            "password" => ["required", "confirmed", Password::min(8)->letters()->numbers()],
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new FailedValidationException($validator);
    }
}
