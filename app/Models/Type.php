<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class, "operator_id");
    }
}
