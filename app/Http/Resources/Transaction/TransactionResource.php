<?php

namespace App\Http\Resources\Transaction;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $operator = Operator::query()->where("id", $this->operator_id)->first();
        return [
            "id" => $this->id,
            "email" => $this->email,
            "operator" => [
                "name" => $operator->name,
                "slug" => $operator->slug,
                "image" => $operator->image
            ],
            "product_name" => $this->product_name,
            "destination" => $this->destination,
            "server_id" => $this->server_id,
            "payment_method" => $this->payment_method,
            "total" => $this->total,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
