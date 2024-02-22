<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "channel_code" => $this->channel_code,
            "name" => $this->name,
            "icon" => $this->icon,
            "object" => json_encode($this->object)
        ];
    }
}
