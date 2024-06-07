<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{

    public function toArray(Request $request): AnonymousResourceCollection
    {
        return TransactionResource::collection($this->collection);
    }
}
