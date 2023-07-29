<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param array $filters
     * @return array
     */
    public function getTransactions(array $filters): array
    {
        return $this->transactionRepository->getFilteredTransactions($filters);
    }
}
