<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\ValueObject;

final class ShopCartProductIdentity
{
    private string $shopCartUuid;

    private string $productUuid;

    public function __construct(string $shopCartUuid, string $productUuid)
    {
        $this->shopCartUuid = $shopCartUuid;
        $this->productUuid = $productUuid;
    }

    public function getShopCartUuid(): string
    {
        return $this->shopCartUuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }
}