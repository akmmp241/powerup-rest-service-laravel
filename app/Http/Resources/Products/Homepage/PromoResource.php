<?php

namespace App\Http\Resources\Products\Homepage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "product_name" => $this->product->name,
            "title" => $this->title,
            "description" => $this->description,
            "product_url" => $this->product_url,
            "image_url" => $this->image_url,
        ];
    }
}
