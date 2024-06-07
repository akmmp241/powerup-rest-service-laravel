<?php

namespace App\Http\Requests\Transaction;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GetTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => ["required", "email"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
