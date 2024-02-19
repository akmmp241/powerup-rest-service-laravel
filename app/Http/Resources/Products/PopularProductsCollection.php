<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PopularProductsCollection extends ResourceCollection
{
    public function toArray(Request $request): AnonymousResourceCollection
    {
        return PopularProductResource::collection($this->collection);
    }
}
