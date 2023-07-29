<?php

namespace App\DataProviders;

interface DataProviderInterface
{
    public function fetchAllTransactions(): array;
}
