<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Factory;

use App\Domain\Product\Product;
use App\Domain\ShopCart\ShopCart;
use App\Domain\ShopCart\ShopCartProduct;
use App\Domain\ShopCart\ValueObject\Money;
use App\Domain\ShopCart\ValueObject\ShopCartProductIdentity;
use Money\Currency as LibMoneyCurrency;
use Money\Money as LibMoney;

final class ShopCartFactory
{
    public static function createNewShopCart(string $uuid): ShopCart
    {
        $money = new LibMoney(0, new LibMoneyCurrency(Money::DEFAULT_SHOP_CART_CURRENCY));
        return new ShopCart(
            $uuid,
            new Money((float)($money->getAmount() / 100), $money->getCurrency()->getCode()),
        );
    }

    public static function createNewShopCartProduct(
        ShopCart $shopCart,
        Product $product,
        int $quantity
    ): ShopCartProduct {
        $money = new LibMoney(100 * $product->getMoney()->getAmount(), new LibMoneyCurrency($product->getMoney()->getCurrencyCode()));

        return new ShopCartProduct(
            new ShopCartProductIdentity($shopCart->getAggregateRootId(), $product->getAggregateRootId()),
            $quantity,
            new Money((float)$quantity * ($money->getAmount() / 100), $money->getCurrency()->getCode()),
            $shopCart,
            $product
        );
    }
}