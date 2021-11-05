<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\ChangeName;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class ChangeNameCommand implements CommandInterface
{
    private string $uuid;
    private string $name;

    public function __construct(string $uuid, string $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
