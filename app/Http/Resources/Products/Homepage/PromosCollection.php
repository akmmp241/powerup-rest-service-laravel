<?php

namespace App\Http\Resources\Products\Homepage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PromosCollection extends ResourceCollection
{
    public function toArray(Request $request): AnonymousResourceCollection
    {
        return PromoResource::collection($this->collection);
    }
}
