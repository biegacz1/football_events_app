<?php

namespace App\domain;

interface EventRepositoryInterface
{
    public function save(MatchEvent $event): void;

    /** @return MatchEvent[] */
    public function findBy(array $criteria): array;
}