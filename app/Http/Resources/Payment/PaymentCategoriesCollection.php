<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCategoriesCollection extends ResourceCollection
{
    public function toArray(Request $request): AnonymousResourceCollection
    {
        return PaymentCategoryResource::collection($this->collection);
    }
}
