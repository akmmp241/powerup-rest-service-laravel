<?php

namespace App\Http\Controllers\Api\Products;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\Homepage\PromosCollection;
use App\Models\Promo;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    use Responses;

    public function getPromos(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Promos",
            data: new PromosCollection(Promo::query()->get())
        );
    }
}
