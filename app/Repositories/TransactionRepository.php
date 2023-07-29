<?php

namespace App\Repositories;

use App\Enums\DataProviders;
use App\Models\ProviderW;
use App\Models\ProviderX;
use App\Models\ProviderY;

class TransactionRepository
{
    /**
     * @param array $filters
     * @return array
     */
    public function getFilteredTransactions(array $filters): array
    {
        $provider = $filters['provider'] ?? null;
        $statusCode = $filters['statusCode'] ?? null;
        $amountMin = $filters['amountMin'] ?? null;
        $amountMax = $filters['amountMax'] ?? null;
        $currency = $filters['currency'] ?? null;

        $transactions = [];

        if ($provider === DataProviders::DATA_PROVIDER_W) {
            $transactions = array_merge($transactions, $this->readFilteredJsonData('DataProviderW.json', ProviderW::class, $statusCode, $amountMin, $amountMax, $currency));
        } elseif ($provider === DataProviders::DATA_PROVIDER_X) {
            $transactions = array_merge($transactions, $this->readFilteredJsonData('DataProviderX.json', ProviderX::class, $statusCode, $amountMin, $amountMax, $currency));
        } elseif ($provider === DataProviders::DATA_PROVIDER_Y) {
            $transactions = array_merge($transactions, $this->readFilteredJsonData('DataProviderY.json', ProviderY::class, $statusCode, $amountMin, $amountMax, $currency));
        } else {
            $transactions = array_merge(
                $this->readFilteredJsonData('DataProviderW.json', ProviderW::class, $statusCode, $amountMin, $amountMax, $currency),
                $this->readFilteredJsonData('DataProviderX.json', ProviderX::class, $statusCode, $amountMin, $amountMax, $currency),
                $this->readFilteredJsonData('DataProviderY.json', ProviderY::class, $statusCode, $amountMin, $amountMax, $currency)
            );
        }
        return $transactions;
    }

    /**
     * @param string $filename
     * @param string $modelClass
     * @param string|null $statusCode
     * @param float|null $amountMin
     * @param float|null $amountMax
     * @param string|null $currency
     * @return array
     */
    private function readFilteredJsonData(string $filename, string $modelClass, ?string $statusCode, ?float $amountMin, ?float $amountMax, ?string $currency): array
    {
        $transactions = [];
        $file = fopen(storage_path('app/' . $filename), 'r');

        $buffer = '';
        while (($jsonLine = fgets($file)) !== false) {
            $jsonLine = trim($jsonLine);
            if (empty($jsonLine)) {
                continue; // Skip empty lines
            }

            $buffer .= $jsonLine;

            // Check if the buffer contains a complete JSON object
            $data = json_decode($buffer, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                continue; // JSON object is not complete yet, continue reading
            }

            foreach ($data as $tr) {
                $transaction = new $modelClass($tr);
                if (($statusCode === null || $transaction->getStatus() === $statusCode)
                    && ($amountMin === null || $transaction->getAmount() >= $amountMin)
                    && ($amountMax === null || $transaction->getAmount() <= $amountMax)
                    && ($currency === null || $transaction->getCurrency() === $currency)
                ) {
                    $transactions[] = $transaction->toArray();
                }
            }
            // Reset the buffer
            $buffer = '';
        }

        fclose($file);
        return $transactions;
    }
}
