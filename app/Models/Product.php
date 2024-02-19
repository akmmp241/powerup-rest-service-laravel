<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "ref_id",
        "type_id",
        "code",
        "name",
        "description"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function promo(): HasOne
    {
        return $this->hasOne(Promo::class, "product_id");
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, "type_id");
    }
}
