<?php

declare(strict_types=1);

namespace App\Domain\Product\Event;

use App\Domain\Product\Product;
use App\Domain\SharedKernel\Event\DomainEventInterface;

final class ProductRemoved implements DomainEventInterface
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}