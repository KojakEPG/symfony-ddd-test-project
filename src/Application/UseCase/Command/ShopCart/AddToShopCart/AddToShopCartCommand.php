<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\ShopCart\AddToShopCart;

use App\Infrastructure\SharedKernel\MessageBus\Command\CommandInterface;

final class AddToShopCartCommand implements CommandInterface
{
    private string $shopCartUuid;
    private string $productUuid;
    private int $quantity;

    public function __construct(string $shopCartUuid, string $productUuid, int $quantity)
    {
        $this->shopCartUuid = $shopCartUuid;
        $this->productUuid = $productUuid;
        $this->quantity = $quantity;
    }

    public function getShopCartUuid(): string
    {
        return $this->shopCartUuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
