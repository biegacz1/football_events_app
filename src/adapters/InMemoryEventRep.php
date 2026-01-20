<?php

namespace App\adapters;

use App\domain\EventRepositoryInterface;
use App\domain\MatchEvent;

class InMemoryEventRep implements EventRepositoryInterface
{
    private array $events = [];

    public function save(MatchEvent $event): void
    {
        $this->events[] = $event;
    }

    public function findEvents(int $matchId): array
    {
        return array_values(
            array_filter($this->events, function (MatchEvent $event) use ($matchId) {
                return $event->matchId === $matchId;
            })
        );
    }

    public function findBy(array $criteria): array
    {
        return $this->events;
    }
}