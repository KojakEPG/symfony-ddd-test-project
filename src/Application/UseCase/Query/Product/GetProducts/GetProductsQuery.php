<?php

declare(strict_types=1);

namespace App\Application\UseCase\Query\Product\GetProducts;

use App\Domain\Product\Repository\Filters;
use App\Domain\Product\Repository\Pagination;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryInterface;

class GetProductsQuery implements QueryInterface
{
    private Filters $filters;
    private Pagination $pagination;

    public function __construct(Filters $filters, Pagination $pagination)
    {
        $this->filters = $filters;
        $this->pagination = $pagination;
    }

    public function getFilters(): Filters
    {
        return $this->filters;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
}
