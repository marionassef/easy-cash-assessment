<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTransactions(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'in:DataProviderW,DataProviderX,DataProviderY',
            'statusCode' => 'in:approved,pending,rejected',
            'amountMin' => 'numeric|min:0',
            'amountMax' => 'numeric|min:0|gt:amountMin',
            'currency' => 'alpha|size:3',
            'limit' => 'integer|min:1|max:100',
            'offset' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $filters = [
            'provider' => $request->input('provider'),
            'statusCode' => $request->input('statusCode'),
            'amountMin' => $request->input('amountMin'),
            'amountMax' => $request->input('amountMax'),
            'currency' => $request->input('currency'),
        ];

        return response()->json($this->transactionService->getTransactions($filters));
    }
}
