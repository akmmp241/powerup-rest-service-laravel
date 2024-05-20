<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "type_id" => $this->type_id,
            "code" => $this->code,
            "price" => $this->price(),
            "name" => $this->name,
            "description" => $this->description
        ];
    }
}
