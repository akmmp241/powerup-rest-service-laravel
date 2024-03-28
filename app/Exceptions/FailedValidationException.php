<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class FailedValidationException extends Exception
{
    protected Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
