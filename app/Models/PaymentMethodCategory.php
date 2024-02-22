<?php

namespace App\Models;

use App\Casts\JsonToArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethodCategory extends Model
{
    use HasFactory;

    protected $table = 'payment_method_categories';

    protected $fillable = [
        "name",
        "code",
        "object"
    ];

    protected $casts = [
        "object" => JsonToArray::class,
        "created_at" => 'datetime',
        "updated_at" => 'datetime'
    ];

    public function payment_methods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, "type", "code");
    }
}
