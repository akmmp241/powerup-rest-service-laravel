<?php

namespace App\Http\Requests\Authentication;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "password" => ["required", "confirmed", Password::min(8)->letters()->numbers()],
            "id" => ["required"]
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
