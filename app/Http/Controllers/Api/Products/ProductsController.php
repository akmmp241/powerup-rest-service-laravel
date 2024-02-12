<?php

namespace App\Http\Controllers\Api\Products;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\CategoriesCollection;
use App\Models\Category;
use App\Services\Products\Categories\CategoriesService;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{
    use Responses;

    public function __construct(
        private readonly CategoriesService $categoriesService,
    )
    {
    }

    public function getCategories(): JsonResponse
    {
        // get data from tokovoucher
        $res = $this->categoriesService->getData();

        // check if data need to update or insert new
        $dataModel = Category::query()->select("ref_id", "name")->get();
        if ($res->toArray() !== $dataModel->toArray()) {
            $this->categoriesService->insert($res);
        }

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Product Categories",
            data: new CategoriesCollection(Category::query()->get())
        );
    }
}
