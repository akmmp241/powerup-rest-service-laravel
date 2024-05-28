<?php

namespace App\Tokovoucher;

use App\Exceptions\FailedCreateTransactionException;
use App\Exceptions\ProductNotFoundException;
use App\Models\TokovoucherProduct;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Http;

trait TokoVoucher
{
    public function getProduct(string $productCode): TokovoucherProduct
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
                    "kode" => $productCode
                ])->get("/produk/code")
                ->throw();
        } catch (ConnectException) {
            throw new ProductNotFoundException();
        }

        if (!$res->ok()) {
            throw new FailedCreateTransactionException($res);
        }

        $data = collect($res->collect()["data"])->filter(function ($val) use ($productCode) {
            return $val["code"] === $productCode;
        });

        $product = new TokovoucherProduct();
        $product->id = $data[0]["id"];
        $product->code = $data[0]["code"];
        $product->name = $data[0]["nama_produk"];
        $product->description = $data[0]["deskripsi"];
        $product->price = $data[0]["price"];
        $product->status = $data[0]["status"];

        return $product;
    }
}
