<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operator extends Model
{
    use HasFactory;

    protected $table = 'operators';

    protected $fillable = [
        "ref_id",
        "category_id",
        "name",
        "image",
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function types(): HasMany
    {
        return $this->hasMany(Type::class, "operator_id");
    }

    public function popular(): HasOne
    {
        return $this->hasOne(PopularProducts::class, "operator_id");
    }
}
