<?php

namespace App\application\handlers;

use App\domain\calculators\FoulStatisticCalculator;
use App\domain\EventRepositoryInterface;
use App\domain\MatchEvent;
use App\domain\MatchEventHandlerInterface;
use App\domain\MatchStatistics;
use App\domain\StatisticsRepositoryInterface;

class FoulEventHandler implements MatchEventHandlerInterface
{
    const string EVENT_TYPE = 'foul';

    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private StatisticsRepositoryInterface $statisticsRepository,
    ) {
    }

    public function supportsEvent(MatchEvent $event): bool
    {
        return $event->type === self::EVENT_TYPE;
    }

    public function handleEvent(MatchEvent $event): void
    {
        $this->eventRepository->save($event);

        $matchStatistics = $this->statisticsRepository->findByEvent($event);
        if (null === $matchStatistics) {
            $matchStatistics = MatchStatistics::fromEvent($event);
        }

        $calculators = [new FoulStatisticCalculator()];
        foreach ($calculators as $calculator) {
            $matchStatistics->recalculateFromEvent(
                $event, $calculator
            );
        }

        $this->statisticsRepository->save($matchStatistics);
    }
}