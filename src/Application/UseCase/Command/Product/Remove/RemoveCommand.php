<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\Remove;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class RemoveCommand implements CommandInterface
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
