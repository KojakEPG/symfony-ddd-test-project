<?php

declare(strict_types=1);

namespace App\Application\UseCase\Query\ShopCart\GetShopCarts;

use App\Domain\ShopCart\Repository\Pagination;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryInterface;

class GetShopCartsQuery implements QueryInterface
{
    private Pagination $pagination;

    public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
}
