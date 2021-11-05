<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel\Domain;

trait AggregateRootTrait
{
    private string $uuid;

    public function getAggregateRootId(): string
    {
        return $this->uuid;
    }
}