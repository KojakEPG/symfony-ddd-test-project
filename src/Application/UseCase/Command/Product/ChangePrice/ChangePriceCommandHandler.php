<?php

declare(strict_types=1);

namespace App\Application\UseCase\Command\Product\ChangePrice;

use App\Domain\Product\Repository\WriteModelRepositoryInterface;
use App\Infrastructure\SharedKernel\MessageBus\Command\CommandHandlerInterface;
use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;
use Money as LibMoney;

final class ChangePriceCommandHandler implements CommandHandlerInterface
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

    public function __invoke(ChangePriceCommand $command): void
    {
        $product = $this->writeModelRepository->getOneByUuid($command->getUuid());

        $product->changePrice(new LibMoney\Money((int)(100 * $command->getAmount()), new LibMoney\Currency($command->getCurrencyCode())));

        $this->writeModelRepository->storeProduct($product);

        $product->publishDomainEvents($this->eventBus);
    }
}
