<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\ValueObject;

final class Money
{
    const DEFAULT_SHOP_CART_CURRENCY = 'PLN';
    
    private float $amount;

    private string $currencyCode;

    public function __construct(float $amount, string $currencyCode)
    {
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}