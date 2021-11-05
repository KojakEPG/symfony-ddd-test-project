<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\ShopCart\Create;

use App\Domain\ShopCart\Factory\ShopCartFactory;
use App\Domain\ShopCart\Repository\WriteModelRepositoryInterface;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandHandlerInterface;
use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;

final class CreateCommandHandler implements CommandHandlerInterface
{
    private WriteModelRepositoryInterface $writeModelRepository;

    private EventBus $eventBus;

    public function __construct(
        WriteModelRepositoryInterface $writeModelRepository,
        EventBus $eventBus
    ) {
        $this->writeModelRepository = $writeModelRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateCommand $command): void
    {
        $shopCart = ShopCartFactory::createNewShopCart($command->getUuid());

        $shopCart->create();

        $this->writeModelRepository->storeShopCart($shopCart);
        $this->writeModelRepository->flush();

        $shopCart->publishDomainEvents($this->eventBus);
    }
}
