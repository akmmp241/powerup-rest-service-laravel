<?php

namespace App\Http\Requests;

use App\Helpers\ResponseCode;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetTypesRequest extends FormRequest
{
    use Responses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "operator_id" => ["required"]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->baseWithError(
            code: ResponseCode::HTTP_BAD_REQUEST,
            message: "Bad Request",
            errors: $validator->errors()
        ));
    }
}
