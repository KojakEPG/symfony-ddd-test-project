<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Repository;

use App\Domain\ShopCart\ShopCart;
use App\Domain\ShopCart\ShopCartProduct;

interface WriteModelRepositoryInterface
{
    public function getOneByUuid(string $uuid): ShopCart;

    public function storeShopCart(ShopCart $shopCart): void;

    public function storeShopCartProduct(ShopCartProduct $shopCartProduct): void;

    public function removeShopCartProduct(ShopCartProduct $shopCartProduct): void;

    public function flush();
}