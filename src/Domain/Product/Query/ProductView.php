<?php

declare(strict_types=1);

namespace App\Domain\Product\Query;

use App\Domain\Product\Product;

final class ProductView
{
    public string $uuid;

    public string $name;

    public string $description;

    public float $amount;

    public string $currencyCode;

    public static function createFromDomain(Product $product): ProductView
    {
        $productView = new self();

        $productView->uuid = $product->getAggregateRootId();
        $productView->name = $product->getInfo()->getName();
        $productView->description = $product->getInfo()->getDescription();
        $productView->amount = $product->getMoney()->getAmount();
        $productView->currencyCode = $product->getMoney()->getCurrencyCode();

        return $productView;
    }
}
