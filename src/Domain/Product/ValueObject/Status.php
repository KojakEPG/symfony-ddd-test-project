<?php

declare(strict_types=1);

namespace App\Domain\Product\ValueObject;

final class Status
{
    const STATUS_ACTIVE = true;
    const STATUS_INACTIVE = false;

    private bool $isActive;

    public function __construct(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}