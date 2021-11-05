<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

final class Filters
{
    private bool $showRemoved;

    public function __construct(bool $showRemoved)
    {
        $this->showRemoved = $showRemoved;
    }

    public function isShowRemoved(): bool
    {
        return $this->showRemoved;
    }
}