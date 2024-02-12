<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "ref_id",
        "name",
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime"
    ];
}
