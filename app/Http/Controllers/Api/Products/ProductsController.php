<?php

namespace App\Http\Controllers\Api\Products;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\GetOperatorsRequest;
use App\Http\Requests\Products\GetProductsRequest;
use App\Http\Requests\Products\GetTypesRequest;
use App\Http\Resources\Products\CategoriesCollection;
use App\Http\Resources\Products\OperatorsCollection;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\TypesCollection;
use App\Models\Category;
use App\Models\Operator;
use App\Models\Product;
use App\Models\Type;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{
    use Responses;

    public function getCategories(): JsonResponse
    {
        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Product Categories",
            data: new CategoriesCollection(Category::query()->get())
        );
    }

    public function getOperators(GetOperatorsRequest $request): JsonResponse
    {
        $categoryId = $request->validated()["category_id"];

        $operators = Operator::query()->with("category")->where("category_id", $categoryId)->get();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Product Operators",
            data: new OperatorsCollection($operators)
        );
    }

    public function getTypes(GetTypesRequest $request): JsonResponse
    {
        $operatorId = $request->validated()["operator_id"];

        $types = Type::query()->with(["operator"])->where("operator_id", $operatorId)->get();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Product Types",
            data: new TypesCollection($types)
        );
    }

    public function getProducts(GetProductsRequest $request): JsonResponse
    {
        $typeId = $request->validated()["type_id"];

        $products = Product::query()->with(["type"])->where("type_id", $typeId)->get();

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Products",
            data: new ProductCollection($products)
        );
    }
}
