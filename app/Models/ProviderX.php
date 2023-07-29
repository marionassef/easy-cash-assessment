<?php

namespace App\Models;

use App\Enums\Statuses;

class ProviderX
{
    private int $amount;
    private string $currency;
    private string $phone;
    private string $status;
    private string $created_at;
    private string $id;

    public function __construct(array $data)
    {
        $this->amount = $data['transactionAmount'];
        $this->currency = $data['Currency'];
        $this->phone = $data['senderPhone'];
        $this->status = $this->mapStatus($data['transactionStatus']);
        $this->created_at = $data['transactionDate'];
        $this->id = $data['transactionIdentification'];
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

    private function mapStatus(int $status): string
    {
        return match ($status) {
            1 => Statuses::APPROVED,
            2 => Statuses::PENDING,
            3 => Statuses::REJECTED,
            default => 'unknown',
        };
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
