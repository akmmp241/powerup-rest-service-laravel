<?php

namespace App\Http\Requests\Authentication;

use App\Exceptions\FailedValidationException;
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
        throw new FailedValidationException($validator);
    }
}
