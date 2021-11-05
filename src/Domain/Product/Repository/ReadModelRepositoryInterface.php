<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Query\ProductView;

interface ReadModelRepositoryInterface
{
    public function getOneByUuid(string $uuid): ProductView;

    /** @return ProductView[] */
    public function getAll(Filters $filters, Pagination $pagination): array;
}