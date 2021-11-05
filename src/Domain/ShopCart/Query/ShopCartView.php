<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Query;

use App\Domain\ShopCart\ShopCart;

final class ShopCartView
{
    public string $uuid;

    public float $amount;

    public string $currencyCode;

    /** @var ShopCartProductView[] */
    public array $products;

    public static function createFromDomain(ShopCart $shopCart): ShopCartView
    {
        $shopCartView = new self();

        $shopCartView->uuid = $shopCart->getAggregateRootId();
        $shopCartView->amount = $shopCart->getMoney()->getAmount();
        $shopCartView->currencyCode = $shopCart->getMoney()->getCurrencyCode();

        $shopCartProductViews = [];
        foreach ($shopCart->getProducts() as $shopCartProduct) {
            $shopCartProductViews[] = ShopCartProductView::createFromDomain($shopCartProduct);
        }
        $shopCartView->products = $shopCartProductViews;

        return $shopCartView;
    }
}
