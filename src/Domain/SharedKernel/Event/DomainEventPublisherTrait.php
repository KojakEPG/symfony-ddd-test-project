<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel\Event;

use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;

trait DomainEventPublisherTrait
{
    /** @var array|DomainEventInterface[] */
    private array $domainEvents = [];

    public function publishDomainEvents(EventBus $eventBus): void
    {
        foreach ($this->domainEvents as $domainEvent) {
            $eventBus->handle($domainEvent);
        }

        $this->clearDomainEvents();
    }

    public function registerDomainEvent(DomainEventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }

    /** @return DomainEventInterface[] */
    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }
}