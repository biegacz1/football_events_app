<?php

namespace App\domain\calculators;

use App\domain\MatchEvent;
use App\domain\StatisticCalculatorInterface;

class FoulStatisticCalculator implements StatisticCalculatorInterface
{
    public function recalculateStatistics(array $data, MatchEvent $matchEvent): array
    {
        if (false === isset($data['fouls'])) {
            $data['fouls'] = 0;
        }

        $data['fouls'] += 1;

        $card = $matchEvent->eventData['card'] ?? null;
        if ($card) {
            if (false === isset($data['cards'])) {
                $data['cards'][$card] = 0;
            }

            $data['cards'][$card] = $data['cards'][$card] + 1;
        }

        return $data;
    }
}