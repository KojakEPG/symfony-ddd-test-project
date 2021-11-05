<?php

declare(strict_types=1);

namespace App\Domain\Product;

use App\Domain\Product\Event\ProductCreated;
use App\Domain\Product\Event\ProductNameChanged;
use App\Domain\Product\Event\ProductPriceChanged;
use App\Domain\Product\Event\ProductRemoved;
use App\Domain\Product\Validation\ProductValidation;
use App\Domain\Product\ValueObject\Info;
use App\Domain\Product\ValueObject\Money;
use App\Domain\Product\ValueObject\Status;
use App\Domain\SharedKernel\Domain\AggregateRootInterface;
use App\Domain\SharedKernel\Domain\AggregateRootTrait;
use App\Domain\SharedKernel\Event\DomainEventPublisherInterface;
use App\Domain\SharedKernel\Event\DomainEventPublisherTrait;
use Money\Money as LibMoney;

class Product implements AggregateRootInterface, DomainEventPublisherInterface
{
    use AggregateRootTrait;
    use DomainEventPublisherTrait;

    private Info $info;

    private Money $money;

    private Status $status;

    public function __construct(string $uuid, Info $info, Money $money, Status $status)
    {
        ProductValidation::assertProductConsistency($uuid, $info, $money, $status);

        $this->uuid = $uuid;
        $this->info = $info;
        $this->money = $money;
        $this->status = $status;
    }

    public function create(): void
    {
        ProductValidation::assertCreateNewProduct($this->getStatus());

        $this->registerDomainEvent(new ProductCreated($this));
    }

    public function changeName(string $name): void
    {
        ProductValidation::assertChangeProductName($name);

        $this->info = new Info($name, $this->info->getDescription());

        $this->registerDomainEvent(new ProductNameChanged($this));
    }

    public function changePrice(LibMoney $money): void
    {
        ProductValidation::assertChangeProductPrice($money);

        $this->money = new Money((float)($money->getAmount() / 100), $money->getCurrency()->getCode());

        $this->registerDomainEvent(new ProductPriceChanged($this));
    }

    public function remove(): void
    {
        ProductValidation::assertRemoveProduct($this->getStatus());

        $this->status = new Status(Status::STATUS_INACTIVE);

        $this->registerDomainEvent(new ProductRemoved($this));
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}