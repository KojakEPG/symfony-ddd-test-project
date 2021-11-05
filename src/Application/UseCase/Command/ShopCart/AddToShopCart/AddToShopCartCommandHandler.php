<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\ShopCart\AddToShopCart;

use App\Domain\Product\Repository\WriteModelRepositoryInterface as ProductWriteModelRepositoryInterface;
use App\Domain\ShopCart\Factory\ShopCartFactory;
use App\Domain\ShopCart\Repository\WriteModelRepositoryInterface as ShopCartWriteModelRepositoryInterface;
use App\Domain\ShopCart\Validation\ShopCartValidation;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandHandlerInterface;
use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;

final class AddToShopCartCommandHandler implements CommandHandlerInterface
{
    private ShopCartWriteModelRepositoryInterface $shopCartWriteModelRepository;
    private ProductWriteModelRepositoryInterface $productWriteModelRepository;

    private EventBus $eventBus;

    public function __construct(
        ShopCartWriteModelRepositoryInterface $shopCartWriteModelRepository,
        ProductWriteModelRepositoryInterface $productWriteModelRepository,
        EventBus $eventBus
    ) {
        $this->shopCartWriteModelRepository = $shopCartWriteModelRepository;
        $this->productWriteModelRepository = $productWriteModelRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(AddToShopCartCommand $command): void
    {
        $shopCart = $this->shopCartWriteModelRepository->getOneByUuid($command->getShopCartUuid());
        $product = $this->productWriteModelRepository->getOneByUuid($command->getProductUuid());

        $shopCartProduct = ShopCartFactory::createNewShopCartProduct($shopCart, $product, $command->getQuantity());

        $shopCartProduct = $shopCart->addToShopCart($shopCartProduct);

        $this->shopCartWriteModelRepository->storeShopCartProduct($shopCartProduct);
        $this->shopCartWriteModelRepository->storeShopCart($shopCart);
        $this->shopCartWriteModelRepository->flush();

        $shopCart->publishDomainEvents($this->eventBus);
    }
}
