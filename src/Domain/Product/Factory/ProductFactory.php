<?php

declare(strict_types=1);

namespace App\Domain\Product\Factory;

use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\Info;
use App\Domain\Product\ValueObject\Money;
use App\Domain\Product\ValueObject\Status;
use Money\Money as LibMoney;

final class ProductFactory
{
    public static function createNewProduct(string $uuid, string $name, string $description, LibMoney $money): Product
    {
        return new Product(
            $uuid,
            new Info($name, $description),
            new Money((float)($money->getAmount() / 100), $money->getCurrency()->getCode()),
            new Status(Status::STATUS_ACTIVE)
        );
    }
}