<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypesCollection extends ResourceCollection
{
    public function toArray(Request $request): AnonymousResourceCollection
    {
        return TypeResource::collection($this->collection);
    }
}
