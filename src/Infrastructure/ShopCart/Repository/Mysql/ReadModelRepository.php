<?php

declare(strict_types=1);

namespace App\Infrastructure\ShopCart\Repository\Mysql;

use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use App\Domain\ShopCart\Query\ShopCartView;
use App\Domain\ShopCart\Repository\Pagination;
use App\Domain\ShopCart\Repository\ReadModelRepositoryInterface;
use App\Domain\ShopCart\ShopCart;
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
        $this->repository = $this->entityManager->getRepository(ShopCart::class);
    }

    public function getOneByUuid(string $uuid): ShopCartView
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('ShopCart')
            ->where('ShopCart.uuid = :uuid')
            ->setParameter('uuid', $uuid);

        $shopCart = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $shopCart) {
            throw new NotFoundException('Resource ' . $uuid . ' not found');
        }

        return ShopCartView::createFromDomain($shopCart);
    }

    /** @return ShopCartView[] */
    public function getAll(Pagination $pagination): array
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('ShopCart')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getPerPage());

        $paginator = new Paginator($queryBuilder->getQuery());

        $viewShopCarts = [];

        foreach ($paginator as $product) {
            $viewShopCarts[] = ShopCartView::createFromDomain($product);
        }

        return $viewShopCarts;
    }
}
