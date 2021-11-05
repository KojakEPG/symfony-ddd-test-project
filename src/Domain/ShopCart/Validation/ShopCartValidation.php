<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Validation;

use App\Domain\SharedKernel\Exception\DomainConsistencyException;
use App\Domain\ShopCart\ShopCart;
use App\Domain\ShopCart\ShopCartProduct;

final class ShopCartValidation
{
    private const MAX_PRODUCTS_QUANTITY = 3;

    public static function assertAddToShopCart(ShopCart $shopCart, ShopCartProduct $shopCartProduct)
    {
        if (!$shopCartProduct->getProduct()->getStatus()->isActive()) {
            throw new DomainConsistencyException('Product must be active');
        }

        if ($shopCartProduct->getQuantity() <= 0) {
            throw new DomainConsistencyException('Product quantity must be positive');
        }

        $currentQuantity = 0;

        foreach ($shopCart->getProducts() as $currentShopCartProduct) {
            /** @var ShopCartProduct $currentShopCartProduct */
            $currentQuantity += $currentShopCartProduct->getQuantity();
        }

        if ($currentQuantity + $shopCartProduct->getQuantity() > self::MAX_PRODUCTS_QUANTITY) {
            throw new DomainConsistencyException('You can have maximum ' . self::MAX_PRODUCTS_QUANTITY . ' products in your shop cart');
        }
    }
}