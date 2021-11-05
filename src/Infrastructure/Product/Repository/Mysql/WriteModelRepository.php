<?php

declare(strict_types=1);

namespace App\Infrastructure\Product\Repository\Mysql;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\WriteModelRepositoryInterface;
use App\Domain\SharedKernel\Exception\Repository\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class WriteModelRepository implements WriteModelRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    protected EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    public function getOneByUuid(string $uuid): Product
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

        return $product;
    }

    public function storeProduct(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
