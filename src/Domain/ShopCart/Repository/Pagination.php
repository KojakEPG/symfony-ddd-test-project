<?php

declare(strict_types=1);

namespace App\Domain\ShopCart\Repository;

final class Pagination
{
    const DEFAULT_PER_PAGE = 5;

    private int $page;

    private int $perPage;

    public function __construct(int $page, int $perPage = self::DEFAULT_PER_PAGE)
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getOffset(): int
    {
        return $this->getPage() * $this->getPerPage() - $this->getPerPage();
    }
}