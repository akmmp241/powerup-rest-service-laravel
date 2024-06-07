<?php

namespace App\Http\Requests\Simulation;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SimulateQRCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "id" => ["required"],
            "amount" => ["required", "numeric"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
