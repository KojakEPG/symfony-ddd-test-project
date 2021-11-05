<?php

declare(strict_types=1);

namespace App\Infrastructure\Product\Repository\Mysql;

use App\Domain\Product\Product;
use App\Domain\Product\Query\ProductView;
use App\Domain\Product\Repository\Filters;
use App\Domain\Product\Repository\Pagination;
use App\Domain\Product\Repository\ReadModelRepositoryInterface;
use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class ReadModelRepository implements ReadModelRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    protected EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    public function getOneByUuid(string $uuid): ProductView
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('product')
            ->where('product.uuid = :uuid')
            ->setParameter('uuid', $uuid);

        $product = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $product) {
            throw new NotFoundException('Resource ' . $uuid . ' not found');
        }

        return ProductView::createFromDomain($product);
    }

    /** @return ProductView[] */
    public function getAll(Filters $filters, Pagination $pagination): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('product')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getPerPage());

        if (!$filters->isShowRemoved()) {
            $queryBuilder
                ->where('product.status.isActive = :active')
                ->setParameter('active', true);
        }

        $paginator = new Paginator($queryBuilder->getQuery());

        $viewProducts = [];

        foreach ($paginator as $product) {
            $viewProducts[] = ProductView::createFromDomain($product);
        }

        return $viewProducts;
    }
}
