<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;

interface WriteModelRepositoryInterface
{
    public function getOneByUuid(string $uuid): Product;

    public function storeProduct(Product $product): void;
}