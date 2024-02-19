<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopularProducts extends Model
{
    use HasFactory;

    protected $table = "popular_products";

    protected $fillable = [
        "operator_id",
        "image",
        "link",
        "description"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class, "operator_id");
    }
}
