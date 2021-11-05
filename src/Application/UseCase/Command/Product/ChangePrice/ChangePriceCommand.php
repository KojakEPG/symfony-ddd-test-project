<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\ChangePrice;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class ChangePriceCommand implements CommandInterface
{
    private string $uuid;
    private float $amount;
    private string $currencyCode;

    public function __construct(string $uuid, float $amount, string $currencyCode)
    {
        $this->uuid = $uuid;
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
