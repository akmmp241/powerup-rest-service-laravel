<?php

namespace App\Http\Requests\Authentication;

use App\Exceptions\FailedValidationException;
use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ForgetPasswordRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return Auth::guest();
    }

    public function rules(): array
    {
        return [
            "email" => ['required', 'email']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
