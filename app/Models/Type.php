<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $fillable = [
        "ref_id",
        "operator_id",
        "name",
        "format_form"
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "type_id");
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class, "operator_id");
    }
}