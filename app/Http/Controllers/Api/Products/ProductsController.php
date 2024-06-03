<?php

namespace App\Http\Controllers\Api\Products;

use App\Exceptions\ProductNotFoundException;
use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\GetOperatorsRequest;
use App\Http\Requests\Products\GetProductsRequest;
use App\Http\Requests\Products\GetTypesRequest;
use App\Http\Resources\Products\CategoriesCollection;
use App\Http\Resources\Products\OperatorResource;
use App\Http\Resources\Products\OperatorsCollection;
use App\Http\Resources\Products\TypesCollection;
use App\Models\Category;
use App\Models\Operator;
use App\Models\Type;
use App\Traits\Responses;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

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

        $operators = Operator::query()->with("category")->where("category_id", $categoryId)->paginate(18);

        $totalPage = round($operators->total() / $operators->perPage());

        return Response::json([
            "success" => true,
            "code" => ResponseCode::HTTP_OK,
            "message" => "Success Get Product Operators",
            "total_page" => $totalPage,
            "current_page" => $operators->currentPage(),
            "data" => new OperatorsCollection($operators),
            "per_page" => $operators->perPage(),
            "total" => $operators->total(),
        ]);
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

//        $products = Product::query()->with(["type"])->where("type_id", $typeId)->get();

        try {
            $res = Http::withHeaders([
                "Accept" => "application/json"
            ])->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                ->retry(3, 500)
                ->timeout(400)
                ->withQueryParameters([
                    "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
                    "signature" => env("TOKOVOUCHER_SIGNATURE"),
                    "id_jenis" => $typeId
                ])->get("/member/produk/list");
        } catch (ConnectException) {
            throw new ProductNotFoundException();
        }

        if ($res->ok()) {
            $data = collect($res->collect()["data"])->map(function ($val) use ($typeId) {
                return [
                    "ref_id" => $val["id"],
                    "type_id" => (int) $typeId,
                    "category_name" => $val["category_name"],
                    "type_name" => $val["jenis_name"],
                    "code" => $val["code"],
                    "name" => $val["nama_produk"],
                    "description" => $val["deskripsi"],
                    "price" => $val["price"],
                    "status" => $val["status"]
                ];
            });
        }

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Products",
            data: $data ?? null
        );
    }

    public function getSingleOperator(string $slug): JsonResponse
    {
        $operator = Operator::query()->with("category")->where("slug", $slug)->first();

        if (!$operator) {
            return $this->base(
                success: false,
                code: ResponseCode::HTTP_NOT_FOUND,
                message: "Not Found"
            );
        }

        return $this->baseWithData(
            success: true,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Operator",
            data: new OperatorResource($operator)
        );
    }

    public function scrapOperator(): void
    {
        try {
            $categories = Category::query()->get();

            $categories->map(function ($val, $key) {
                $res = Http::withHeaders([
                    "Accept" => "application/json"
                ])->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                    ->retry(3, 500)
                    ->timeout(400)
                    ->withQueryParameters([
                        "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
                        "signature" => env("TOKOVOUCHER_SIGNATURE"),
                        "id" => $val->ref_id
                ])->get("/member/produk/operator/list");

                $data = collect($res->collect()["data"]);

                $data->map(function ($value) use ($val) {
                    Operator::query()->create([
                        "ref_id" => $value["id"],
                        "category_id" => $val->id,
                        "name" => $value["nama"],
                        "slug" => str_replace(" ", "-", strtolower($value["nama"])),
                        "image" => $value["logo"],
                        "banner" => "http://localhost:8000/storage/images/hbdahsdj.svg",
                        "description" => ""
                    ]);
                });
            });

            dd(Operator::query()->get());
        } catch (ConnectionException) {
            return;
        }
    }

    public function scrapTypes()
    {
        try {
            $operators = Operator::query()->where("ref_id", ">=", 208)->orderBy("ref_id")->get();

            $operators->map(function ($val) {
                Log::info("operator ref id $val->ref_id");
                $res = Http::withHeaders([
                    "Accept" => "application/json"
                ])->baseUrl(env("TOKOVOUCHER_BASE_URL"))
                    ->retry(3, 500)
                    ->timeout(400)
                    ->withQueryParameters([
                        "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
                        "signature" => env("TOKOVOUCHER_SIGNATURE"),
                        "id" => $val->ref_id
                    ])->get("/member/produk/jenis/list");

                if ($res->json()["status"] === 0) {
                    return;
                }

                $data = collect($res->collect()["data"]);
                Log::info(json_encode($data, JSON_PRETTY_PRINT));

                $data->map(function ($value) use ($val) {
                    Type::query()->create([
                        "ref_id" => $value["id"],
                        "operator_id" => $val->id,
                        "name" => $value["nama"],
                        "format_form" => $value["format_form"]
                    ]);
                });
            });

            dd(Type::query()->get());
        } catch (ConnectionException) {
            return;
        }
    }
}
