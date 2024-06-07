<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Exceptions\TransactionNotFoundException;
use App\Helpers\ResponseCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\GetTransactionRequest;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function getTransactions(GetTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $transaction = Transaction::query()->where("email", $data["email"])->get();

        if ($transaction->isEmpty()) throw new TransactionNotFoundException();

        return $this->baseWithData(
            success: false,
            code: ResponseCode::HTTP_OK,
            message: "Success Get Transaction",
            data: new TransactionCollection($transaction)
        );
    }
}
