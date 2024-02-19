<?php

namespace App\Http\Controllers\Api\Products;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\BannersCollection;
use App\Http\Resources\Products\Homepage\PromosCollection;
use App\Http\Resources\Products\PopularProductsCollection;
use App\Models\Banner;
use App\Models\PopularProducts;
use App\Models\Promo;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;

class HomepageController extends Controller
{
    use Responses;

    public function getPromos(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Promos",
            data: new PromosCollection(Promo::query()->with(["product"])->get())
        );
    }

    public function getPopularProducts(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Popular Products",
            data: new PopularProductsCollection(PopularProducts::query()->with("operator")->get())
        );
    }

    public function getHomeBanners(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Images",
            data: new BannersCollection(Banner::query()->get())
        );
    }
}
