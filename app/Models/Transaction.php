<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        "id",
        "user_id",
        "product_id",
        "product_name",
        "destination",
        "server_id",
        "payment_method",
        "total",
        "status",
        "failure_code",
        "mobile_number"
    ];
}