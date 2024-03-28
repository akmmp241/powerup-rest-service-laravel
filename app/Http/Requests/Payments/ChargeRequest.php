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
            "product_id" => ["required", "numeric"],
            "destination" => ["required", "numeric"],
            "channel_code" => ["required"],
            "total" => ["required", "numeric"],
            "mobile_number" => ["required"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }


}
