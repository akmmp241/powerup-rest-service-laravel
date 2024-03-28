<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class FailedCreateTransactionException extends Exception
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
