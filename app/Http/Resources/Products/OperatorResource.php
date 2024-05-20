<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "ref_id" => $this->ref_id,
            "category_id" => $this->category_id,
            "category_name" => $this->category->name,
            "name" => $this->name,
            "slug" => str_replace(" ", "-", strtolower($this->name)),
            "image" => $this->image
        ];
    }
}
