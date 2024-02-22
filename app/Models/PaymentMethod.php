<?php

namespace App\Models;

use App\Casts\JsonToArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "type",
        "channel_code",
        "name",
        "icon",
        "object"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
        "object" => JsonToArray::class
    ];

    public function payment_category(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodCategory::class, "type", "code");
    }
}
