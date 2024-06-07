<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        "id",
        "xendit_ref_id",
        "tokovoucher_ref_id",
        "user_id",
        "email",
        "operator_id",
        "product_code",
        "product_name",
        "destination",
        "server_id",
        "payment_method",
        "total",
        "status",
        "paid_at",
        "failure_code",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "paid_at" => "datetime",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];
}
