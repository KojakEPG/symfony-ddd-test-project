<?php

declare(strict_types=1);

namespace App\Application\UseCase\Query\Product\GetProducts;

use App\Domain\Product\Query\ProductView;
use App\Domain\Product\Repository\ReadModelRepositoryInterface;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryHandlerInterface;

class GetProductsHandler implements QueryHandlerInterface
{
    private ReadModelRepositoryInterface $readModelRepository;

    public function __construct(ReadModelRepositoryInterface $readModelRepository)
    {
        $this->readModelRepository = $readModelRepository;
    }

    /** @return ProductView[] */
    public function __invoke(GetProductsQuery $query): array
    {
        return $this->readModelRepository->getAll($query->getFilters(), $query->getPagination());
    }
}
