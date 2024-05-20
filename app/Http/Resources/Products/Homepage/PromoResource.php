<?php

namespace App\Http\Resources\Products\Homepage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PromoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $percentage = $this->percentage;
        $price = 10000;

        $finalPrice = round($price - (round($price) * ($percentage/100)));

        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "product_name" => "hai",
            "title" => $this->title,
            "description" => $this->description,
            "percentage" => $percentage,
            "product_url" => $this->product_url,
            "product_price" => $price,
            "final_price" => $finalPrice,
            "image_url" => $this->image_url,
        ];
    }
}
