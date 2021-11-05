<?php

declare(strict_types=1);

namespace App\Domain\SharedKernel\Event;

use App\Infrastructure\SharedKernel\MessageBus\Event\EventBus;

interface DomainEventPublisherInterface
{
    public function publishDomainEvents(EventBus $eventBus): void;

    public function registerDomainEvent(DomainEventInterface $domainEvent): void;

    public function clearDomainEvents(): void;
}