<?php

namespace App\Http\Requests\Payments;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "operator_id" => ["required"],
            "product_code" => ["required"],
            "destination" => ["required"],
            "server_id" => [],
            "channel_code" => ["required"],
            "total" => ["required", "numeric"],
            "email" => []
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }


}
