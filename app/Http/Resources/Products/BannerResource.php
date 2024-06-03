<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "image" => env("APP_URL") . $this->image,
            "description" => $this->description
        ];
    }
}
