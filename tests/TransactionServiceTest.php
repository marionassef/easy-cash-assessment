<?php

namespace Tests;

use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Mockery;

class TransactionServiceTest extends TestCase
{
    public function testGetTransactions(): void
    {
        $filters = [
            'provider' => 'DataProviderW',
            'statusCode' => 'approved',
            'amountMin' => 100,
            'amountMax' => 500,
            'currency' => 'USD',
        ];

        $expectedTransactions = [
            ['transactionId' => 1, 'amount' => 200, 'currency' => 'USD', 'status' => 'approved'],
            ['transactionId' => 2, 'amount' => 150, 'currency' => 'USD', 'status' => 'approved'],
        ];

        $transactionRepository = Mockery::mock(TransactionRepository::class);
        $transactionRepository
            ->shouldReceive('getFilteredTransactions')
            ->with($filters)
            ->once()
            ->andReturn($expectedTransactions);

        $transactionService = new TransactionService($transactionRepository);
        $transactions = $transactionService->getTransactions($filters);

        $this->assertEquals($expectedTransactions, $transactions);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
