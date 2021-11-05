<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Query;

use App\Domain\ShopCart\ShopCartProduct;

final class ShopCartProductView
{
    public string $productUuid;

    public string $productName;

    public int $quantity;

    public float $amount;

    public string $currencyCode;

    public static function createFromDomain(ShopCartProduct $shopCartProduct): ShopCartProductView
    {
        $shopCartProductView = new self();

        $shopCartProductView->productUuid = $shopCartProduct->getIdentity()->getProductUuid();
        $shopCartProductView->productName = $shopCartProduct->getProduct()->getInfo()->getName();
        $shopCartProductView->quantity = $shopCartProduct->getQuantity();
        $shopCartProductView->amount = $shopCartProduct->getMoney()->getAmount();
        $shopCartProductView->currencyCode = $shopCartProduct->getMoney()->getCurrencyCode();

        return $shopCartProductView;
    }
}
