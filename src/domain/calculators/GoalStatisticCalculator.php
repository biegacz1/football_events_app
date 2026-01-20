<?php

namespace App\domain\calculators;

use App\domain\MatchEvent;
use App\domain\StatisticCalculatorInterface;

class GoalStatisticCalculator implements StatisticCalculatorInterface
{
    public function recalculateStatistics(array $data, MatchEvent $matchEvent): array
    {
        if (false === isset($data['goals'])) {
            $data['goals'] = 0;
        }

        $data['goals'] += 1;
        return $data;
    }
}