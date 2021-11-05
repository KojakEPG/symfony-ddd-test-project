<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Event;

use App\Domain\SharedKernel\Event\DomainEventInterface;
use App\Domain\ShopCart\ShopCart;

final class ShopCartCleared implements DomainEventInterface
{
    private ShopCart $shopCart;

    public function __construct(ShopCart $shopCart)
    {
        $this->shopCart = $shopCart;
    }
    
    public function getShopCart(): ShopCart
    {
        return $this->shopCart;
    }
}