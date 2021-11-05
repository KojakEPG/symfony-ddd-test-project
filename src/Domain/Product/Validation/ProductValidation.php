<?php

declare(strict_types=1);

namespace App\Domain\Product\Validation;

use App\Domain\Product\ValueObject\Info;
use App\Domain\Product\ValueObject\Money;
use App\Domain\Product\ValueObject\Status;
use App\Domain\SharedKernel\Exception\DomainConsistencyException;
use Money\Money as LibMoney;

final class ProductValidation
{
    private const ALLOWED_CURRENCIES = ['PLN'];

    public static function assertProductConsistency(string $uuid, Info $info, Money $money, Status $status): void
    {
        if (empty($uuid)) {
            throw new DomainConsistencyException('UUID is empty');
        }

        if ('' === $info->getName()) {
            throw new DomainConsistencyException('Product name is required');
        }

        if ('' === $info->getDescription()) {
            throw new DomainConsistencyException('Product description is required');
        }

        if ($money->getAmount() <= 0.0) {
            throw new DomainConsistencyException('Product amount must be positive');
        }

        if (!in_array($money->getCurrencyCode(), self::ALLOWED_CURRENCIES)) {
            throw new DomainConsistencyException('Product currency must be one of following: ' . implode(', ', self::ALLOWED_CURRENCIES));
        }
    }

    public static function assertCreateNewProduct(Status $status)
    {
        if (!$status->isActive()) {
            throw new DomainConsistencyException('New product must be active');
        }
    }

    public static function assertChangeProductName(string $name)
    {
        if ('' === $name) {
            throw new DomainConsistencyException('Product name is required');
        }
    }

    public static function assertChangeProductPrice(LibMoney $money)
    {
        if ($money->getAmount() <= 0.0) {
            throw new DomainConsistencyException('Product amount must be positive');
        }

        if (!in_array($money->getCurrency()->getCode(), self::ALLOWED_CURRENCIES)) {
            throw new DomainConsistencyException('Product currency must be one of following: ' . implode(', ', self::ALLOWED_CURRENCIES));
        }
    }

    public static function assertRemoveProduct(Status $status)
    {
        if (!$status->isActive()) {
            throw new DomainConsistencyException('Removed product cannot be removed');
        }
    }
}