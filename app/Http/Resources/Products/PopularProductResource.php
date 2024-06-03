<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "operator_id" => $this->operator_id,
            "operator_name" => $this->operator->name,
            "title" => $this->title,
            "image_url" => env("APP_URL") . $this->image,
            "description" => $this->description,
            "link" => $this->link
        ];
    }
}
