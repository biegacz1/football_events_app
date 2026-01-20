<?php

namespace App\adapters;

use App\domain\MatchEvent;
use App\domain\PublisherInterface;

class InMemoryPublisherAdapter implements PublisherInterface
{
    private array $events = [];

    public function publishNotification(MatchEvent $event): void
    {
        $this->events[] = $event;
    }
}