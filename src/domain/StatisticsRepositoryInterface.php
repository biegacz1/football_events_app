<?php

namespace App\domain;

interface StatisticsRepositoryInterface
{
    public function findByEvent(MatchEvent $event): ?MatchStatistics;

    public function findByCriteria(array $criteria): array;

    public function save(MatchStatistics $matchStatistics): void;
}