<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\ShopCart\Create;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class CreateCommand implements CommandInterface
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
