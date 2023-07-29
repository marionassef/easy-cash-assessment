<?php

namespace Tests;

use App\Http\Controllers\TransactionController;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    public function testGetTransactionsWithValidData(): void
    {
        $request = new Request([
            'provider' => 'DataProviderW',
            'statusCode' => 'approved',
            'amountMin' => 100,
            'amountMax' => 500,
            'currency' => 'USD',
        ]);

        $transactionService = Mockery::mock(TransactionService::class);
        $transactionService
            ->shouldReceive('getTransactions')
            ->with([
                'provider' => 'DataProviderW',
                'statusCode' => 'approved',
                'amountMin' => 100,
                'amountMax' => 500,
                'currency' => 'USD',
            ])
            ->once()
            ->andReturn(['transaction1', 'transaction2']);

        $controller = new TransactionController($transactionService);
        $response = $controller->getTransactions($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['transaction1', 'transaction2'], json_decode($response->getContent(), true));
    }

    public function testGetTransactionsWithInvalidData(): void
    {
        $request = new Request([
            'provider' => 'InvalidProvider',
            'statusCode' => 'invalidStatus',
            'amountMin' => 'not_a_number',
            'amountMax' => '50', // This should be less than amountMin
            'currency' => 'US',
        ]);

        $validator = Validator::make([], []); // Create an empty validator instance

        $this->app->instance('validator', $validator);

        $controller = new TransactionController(Mockery::mock(TransactionService::class));
        $response = $controller->getTransactions($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
