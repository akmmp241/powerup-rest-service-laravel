<?php

namespace App\Http\Resources\Payment;

use App\Models\Operator;
use App\Models\TokovoucherProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

class TransactionResource extends JsonResource
{
    private TokovoucherProduct $product;
    private Operator|Model $operator;

    public function setProduct(TokovoucherProduct $product): void
    {
        $this->product = $product;
    }

    public function setOperator(Model|Operator $operator): void
    {
        $this->operator = $operator;
    }

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id ?? null,
            "email" => $this->email,
            "product" => [
                "code" => $this->product_code,
                "name" => $this->product->name,
                "operator_name" => $this->operator->name,
                "operator_image" => $this->operator->image
            ],
            "destination" => $this->destination,
            "server_id" => $this->server_id,
            "payment_method" => $this->payment_method,
            "total" => $this->total,
            "status" => $this->status,
            "paid_at" => $this->paid_at ? $this->paid_at->format("d M Y H:i") : null,
            "failure_code" => $this->failure_code,
            "created_at" => $this->created_at->format("d M Y H:i"),
            "updated_at" => $this->updated_at->format("d M Y H:i")
        ];
    }
}
