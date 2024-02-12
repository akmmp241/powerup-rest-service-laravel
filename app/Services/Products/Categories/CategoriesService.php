<?php

namespace App\Services\Products\Categories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CategoriesService
{
    public function getData(): Collection
    {
        $res = Http::withQueryParameters([
            "member_code" => env("TOKOVOUCHER_MEMBER_CODE"),
            "signature" => env("TOKOVOUCHER_SIGNATURE"),
        ])->contentType("application/json")
            ->baseUrl(env("TOKOVOUCHER_BASE_URL"))
            ->get("/member/produk/category/list")->collect();

        return $this->renameKey($res);
    }

    public function renameKey(Collection $data): Collection
    {
        return collect($data->get("data"))->map(function ($val) {
            return [
                "ref_id" => $val["id"],
                "name" => $val["nama"],
            ];
        });
    }

    public function insert($data): void
    {
        Category::query()->delete();
        try {
            DB::beginTransaction();
            Category::query()->upsert($data->toArray(), [
                "ref_id", "name"
            ], ["ref_id", "name"]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
