<?php

namespace App\Models;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Http;

class Product extends Model
{
    use HasFactory;

    public ?string $price = null;

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

    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class, "icon_id");
    }

    public function price(): ?string
    {
        try {
            $res = Http::withHeaders([
                "Accept" => "application/json"
            ])->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                ->retry(3, 500)
                ->timeout(400)
                ->withQueryParameters([
                    "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
                    "signature" => env("TOKOVOUCHER_SIGNATURE"),
                    "kode" => $this->code
                ])->get("/produk/code");
        } catch (ConnectException) {
            return null;
        }

        if ($res->ok()) {
            $data = collect($res->collect()["data"])->filter(function ($val) {
                return $val["code"] === $this->code;
            })->first();

            return $data["price"];
        }

        return null;
    }
}
