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
    public function getTransactions(array $filters, int $limit = 10, int $offset = 0): array
    {
        return $this->transactionRepository->getFilteredTransactions($filters, $limit, $offset);
    }
}
