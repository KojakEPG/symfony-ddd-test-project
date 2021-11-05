<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\ChangeName;

use App\Domain\Product\Repository\WriteModelRepositoryInterface;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandHandlerInterface;
use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;

final class ChangeNameCommandHandler implements CommandHandlerInterface
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

    public function __invoke(ChangeNameCommand $command): void
    {
        $product = $this->writeModelRepository->getOneByUuid($command->getUuid());

        $product->changeName($command->getName());

        $this->writeModelRepository->storeProduct($product);

        $product->publishDomainEvents($this->eventBus);
    }
}
