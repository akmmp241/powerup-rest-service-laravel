<?php

namespace App\Http\Requests\Authentication;

use App\Exceptions\FailedValidationException;
use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class VerifyEmailRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            "token" => ['required']
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new FailedValidationException($validator);
    }
}
