<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "code" => $this->code,
            "object" => json_encode($this->object),
            "channel_list" => (new PaymentMethodsCollection($this->payment_methods))->jsonSerialize()
        ];
    }
}
