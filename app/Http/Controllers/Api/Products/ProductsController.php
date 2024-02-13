<?php

namespace App\Http\Controllers\Api\Products;

use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetOperatorsRequest;
use App\Http\Requests\GetTypesRequest;
use App\Http\Resources\Products\CategoriesCollection;
use App\Http\Resources\Products\OperatorsCollection;
use App\Http\Resources\TypesCollection;
use App\Models\Category;
use App\Models\Operator;
use App\Models\Type;
use App\Traits\Responses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

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

    public function getProducts()
    {
        $types = Type::query()->get();

        $types->map(function ($val) {
            $res = Http::withQueryParameters([
                "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
                "signature" => env("TOKOVOUCHER_SIGNATURE"),
                "id_jenis" => $val["ref_id"]
            ])->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                ->get("/member/produk/list")->json();

            $data = collect($res["data"])->filter(function ($key) {
                return $key["status"] !== 0;
            })->map(function ($value) use ($val) {

            });
        });
    }
}
