<?php

declare(strict_types=1);

namespace App\Domain\ShopCart;

use App\Domain\Product\Product;
use App\Domain\ShopCart\ValueObject\Money;
use App\Domain\ShopCart\ValueObject\ShopCartProductIdentity;
use Money\Currency as LibCurrency;
use Money\Money as LibMoney;

class ShopCartProduct
{
    private ShopCartProductIdentity $identity;
    private int $quantity;
    private Money $money;
    private ShopCart $shopCart;
    private Product $product;

    public function __construct(
        ShopCartProductIdentity $identity,
        int $quantity,
        Money $money,
        ShopCart $shopCart,
        Product $product
    ) {
        $this->identity = $identity;
        $this->quantity = $quantity;
        $this->money = $money;
        $this->shopCart = $shopCart;
        $this->product = $product;
    }

    public function addToQuantity(int $quantity): void
    {
        $this->quantity += $quantity;

        $productLibMoney = new LibMoney($this->getQuantity() * (100 * $this->product->getMoney()->getAmount()), new LibCurrency($this->product->getMoney()->getCurrencyCode()));

        $this->money = new Money((float)($productLibMoney->getAmount() / 100), $productLibMoney->getCurrency()->getCode());
    }

    public function getIdentity(): ShopCartProductIdentity
    {
        return $this->identity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getShopCart(): ShopCart
    {
        return $this->shopCart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}