<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\Create;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class CreateCommand implements CommandInterface
{
    private string $uuid;
    private string $name;
    private string $description;
    private float $amount;
    private string $currencyCode;

    public function __construct(string $uuid, string $name, string $description, float $amount, string $currencyCode)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
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
