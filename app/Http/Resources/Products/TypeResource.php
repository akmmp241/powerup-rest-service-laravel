<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "ref_id" => $this->ref_id,
            "operator_id" => $this->operator_id,
            "operator_name" => $this->operator->name,
            "name" => $this->name,
            "format_form" => $this->format_form
        ];
    }
}
