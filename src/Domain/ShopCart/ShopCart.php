<?php

declare(strict_types=1);

namespace App\Domain\ShopCart;

use App\Domain\SharedKernel\Domain\AggregateRootInterface;
use App\Domain\SharedKernel\Domain\AggregateRootTrait;
use App\Domain\SharedKernel\Event\DomainEventPublisherInterface;
use App\Domain\SharedKernel\Event\DomainEventPublisherTrait;
use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use App\Domain\ShopCart\Event\ShopCartCreated;
use App\Domain\ShopCart\Validation\ShopCartValidation;
use App\Domain\ShopCart\ValueObject\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Money\Money as LibMoney;
use Money\Currency as LibCurrency;

final class ShopCart implements AggregateRootInterface, DomainEventPublisherInterface
{
    use AggregateRootTrait;
    use DomainEventPublisherTrait;

    private Money $money;

    private Collection $products;

    public function __construct(string $uuid, Money $money)
    {
        $this->uuid = $uuid;
        $this->money = $money;
        $this->products = new ArrayCollection();
    }

    public function create(): void
    {
        $this->registerDomainEvent(new ShopCartCreated($this));
    }

    public function addToShopCart(ShopCartProduct $shopCartProduct): ShopCartProduct
    {
        ShopCartValidation::assertAddToShopCart($this, $shopCartProduct);

        $currentShopCartProduct = $this->getShopCartProductByProductUuid($shopCartProduct->getIdentity()->getProductUuid());

        if (null === $currentShopCartProduct) {
            $this->products->add($shopCartProduct);
        } else {
            $currentShopCartProduct->addToQuantity($shopCartProduct->getQuantity());
            $shopCartProduct = $currentShopCartProduct;
        }

        $this->recalculateShopCart();

        return $shopCartProduct;
    }

    public function removeFromShopCart(string $productUuid): ShopCartProduct
    {
        $currentShopCartProduct = $this->getShopCartProductByProductUuid($productUuid);

        if (null === $currentShopCartProduct) {
            throw new NotFoundException('Product ' . $productUuid . ' not found in shop cart');
        }

        $this->products->removeElement($currentShopCartProduct);

        $this->recalculateShopCart();

        return $currentShopCartProduct;
    }

    private function recalculateShopCart(): void
    {
        $shopCartLibMoney = new LibMoney(0, new LibCurrency($this->getMoney()->getCurrencyCode()));

        foreach ($this->getProducts() as $shopCartProduct) {
            /** @var ShopCartProduct $shopCartProduct */
            $shopCartProductLibMoney = new LibMoney((100 * $shopCartProduct->getMoney()->getAmount()), new LibCurrency($shopCartProduct->getMoney()->getCurrencyCode()));
            $shopCartLibMoney = $shopCartLibMoney->add($shopCartProductLibMoney);
        }

        $this->money = new Money((float)($shopCartLibMoney->getAmount() / 100), $shopCartLibMoney->getCurrency()->getCode());
    }

    private function getShopCartProductByProductUuid(string $productUuid): ?ShopCartProduct
    {
        foreach ($this->getProducts() as $currentShopCartProduct) {
            /** @var ShopCartProduct $currentShopCartProduct */
            if ($currentShopCartProduct->getIdentity()->getProductUuid() === $productUuid) {
                return $currentShopCartProduct;
            }
        }

        return null;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}