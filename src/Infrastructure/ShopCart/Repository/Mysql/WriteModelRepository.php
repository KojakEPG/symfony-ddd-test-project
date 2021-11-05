<?php

declare(strict_types=1);

namespace App\Infrastructure\ShopCart\Repository\Mysql;

use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use App\Domain\ShopCart\Repository\WriteModelRepositoryInterface;
use App\Domain\ShopCart\ShopCart;
use App\Domain\ShopCart\ShopCartProduct;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class WriteModelRepository implements WriteModelRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    protected EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(ShopCart::class);
    }

    public function getOneByUuid(string $uuid): ShopCart
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

        return $shopCart;
    }

    public function storeShopCart(ShopCart $shopCart): void
    {
        $this->entityManager->persist($shopCart);
    }

    public function storeShopCartProduct(ShopCartProduct $shopCartProduct): void
    {
        $this->entityManager->persist($shopCartProduct);
    }

    public function removeShopCartProduct(ShopCartProduct $shopCartProduct): void
    {
        $this->entityManager->remove($shopCartProduct);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
