<?php

namespace App\adapters;

use App\domain\MatchEvent;
use App\domain\MatchStatistics;
use App\domain\StatisticsRepositoryInterface;

class InMemoryStatisticsRep implements StatisticsRepositoryInterface
{
    private array $statistics = [];

    public function findByEvent(MatchEvent $event): ?MatchStatistics
    {
        return $this->statistics[$event->matchId][$event->teamId] ?? null;
    }

    public function findByCriteria(array $criteria): array
    {
        if (isset($criteria['match_id']) && !isset($criteria['team_id'])) {
            return $this->statistics[$criteria['match_id']] ?? [];
        } else if (isset($criteria['match_id']) && isset($criteria['team_id'])) {
            return $this->statistics[$criteria['match_id']][$criteria['team_id']] ?? [];
        }

        return [];
    }

    public function save(MatchStatistics $matchStatistics): void
    {
        $this->statistics[$matchStatistics->getMatchId()][$matchStatistics->getTeam()] = $matchStatistics;
    }
}