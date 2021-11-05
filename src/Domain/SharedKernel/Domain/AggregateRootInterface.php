<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel\Domain;

interface AggregateRootInterface
{
    public function getAggregateRootId(): string;
}