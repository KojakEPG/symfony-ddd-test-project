<?php

declare(strict_types=1);

namespace App\Application\UseCase\Query\ShopCart\GetShopCarts;

use App\Domain\ShopCart\Query\ShopCartView;
use App\Domain\ShopCart\Repository\ReadModelRepositoryInterface;
use App\Infrastructure\SharedKernel\MessageBus\Query\QueryHandlerInterface;

class GetShopCartsHandler implements QueryHandlerInterface
{
    private ReadModelRepositoryInterface $readModelRepository;

    public function __construct(ReadModelRepositoryInterface $readModelRepository)
    {
        $this->readModelRepository = $readModelRepository;
    }

    /** @return ShopCartView[] */
    public function __invoke(GetShopCartsQuery $query): array
    {
        return $this->readModelRepository->getAll($query->getPagination());
    }
}
