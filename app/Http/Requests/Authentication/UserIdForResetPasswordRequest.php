<?php

namespace App\Http\Requests\Authentication;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserIdForResetPasswordRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "token" => ["required"]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException($this->baseWithError(
            success: false,
            code: ResponseCode::HTTP_BAD_REQUEST,
            message: "Bad Request",
            errors: $validator->errors()
        ));
    }
}
