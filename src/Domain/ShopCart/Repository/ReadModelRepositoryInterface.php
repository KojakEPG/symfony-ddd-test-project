<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Repository;

use App\Domain\ShopCart\Query\ShopCartView;

interface ReadModelRepositoryInterface
{
    public function getOneByUuid(string $uuid): ShopCartView;

    /** @return ShopCartView[] */
    public function getAll(Pagination $pagination): array;
}