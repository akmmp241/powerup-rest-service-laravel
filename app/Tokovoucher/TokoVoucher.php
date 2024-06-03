<?php

namespace App\Tokovoucher;

use App\Helpers\ResponseCode;
use App\Models\TokovoucherProduct;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Exceptions\HttpResponseException;
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
        } catch (RequestException) {
            throw new HttpResponseException($this->base(
                success: false,
                code: ResponseCode::HTTP_INTERNAL_SERVER_ERROR,
                message: "Something Wrong"
            ));
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
