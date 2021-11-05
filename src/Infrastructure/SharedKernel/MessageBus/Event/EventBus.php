<?php

declare(strict_types=1);

namespace App\Infrastructure\SharedKernel\MessageBus\Event;

use App\Domain\SharedKernel\Event\DomainEventInterface;
use App\Infrastructure\SharedKernel\MessageBus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class EventBus
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /** @throws Throwable */
    public function handle(DomainEventInterface $event): void
    {
        try {
            $this->eventBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
        }
    }
}
