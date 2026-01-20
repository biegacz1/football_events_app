<?php

namespace App\domain;

interface StatisticCalculatorInterface
{
    /** Calculates update statistics based on provided event */
    public function recalculateStatistics(array $data, MatchEvent $matchEvent): array;
}