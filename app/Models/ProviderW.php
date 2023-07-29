<?php

namespace App\Models;

use App\Enums\Statuses;

class ProviderW
{
    private int $amount;
    private string $currency;
    private string $phone;
    private string $status;
    private string $created_at;
    private int $id;

    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->phone = $data['phone'];
        $this->status = $data['status'];
        $this->created_at = $data['created_at'];
        $this->id = $data['id'];
    }

    // Getter methods
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getId(): string
    {
        return $this->id;
    }

    private function mapStatus(string $status): string
    {
        return match ($status) {
            'done' => Statuses::APPROVED,
            'wait' => Statuses::PENDING,
            'nope' => Statuses::REJECTED,
            default => 'unknown',
        };
    }

    public function getProvider(): string
    {
        return 'DataProviderW';
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'id' => $this->id,
        ];
    }
}
