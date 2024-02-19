<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    use HasFactory;

    protected $table = "promos";

    protected $fillable = [
        "product_id",
        "title",
        "percentage",
        "description",
        "product_url",
        "image_url"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
