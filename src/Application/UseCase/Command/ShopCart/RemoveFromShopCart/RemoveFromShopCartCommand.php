<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\ShopCart\RemoveFromShopCart;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class RemoveFromShopCartCommand implements CommandInterface
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
