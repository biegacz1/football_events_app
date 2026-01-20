<?php

namespace Tests;

use App\adapters\InMemoryPublisherAdapter;
use App\adapters\InMemoryEventRep;
use App\adapters\InMemoryStatisticsRep;
use App\adapters\SimpleMessageBus;
use App\adapters\TransactionalSrv;
use App\application\handlers\FoulEventHandler;
use App\application\handlers\GoalEventHandler;
use App\application\MatchEventHandler;
use App\domain\calculators\GoalStatisticCalculator;
use App\domain\MatchEvent;
use App\domain\MatchStatistics;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{

    public function testMatchEventHandler(): void
    {
        $store = new InMemoryEventRep();
        $publisher = new InMemoryPublisherAdapter();
        $statisticsRep = new InMemoryStatisticsRep();
        $transactionalSrv = new TransactionalSrv();
        $statisticsRep->save(
            new MatchStatistics(
                'Real Madrid',
                13,
                ['goals' => 2]
            )
        );
        $foulHandler = new FoulEventHandler($store, $statisticsRep);
        $goalHandler = new GoalEventHandler($store, $statisticsRep);
        $handler = new MatchEventHandler([
            $foulHandler, $goalHandler
        ], $publisher, $transactionalSrv);

        $matchEvent = new MatchEvent(
            'goal',
            'Real Madrid',
            14,
            10,
            13,
             [
                'scorer' => 'Vini Junior',
                'assisting_player' => 'Rodrygo Goes',
            ],
            time(),
        );

        $bus = new SimpleMessageBus([$handler]);

        $bus->dispatch($matchEvent);

        $events = $store->findBy([]);

        $this->assertEquals($events[0]->type, 'goal');

        // asserts statistics calculations
        $statistics = $statisticsRep->findByEvent($matchEvent);

        $this->assertEquals($statistics->getData()['goals'], 3);
    }

    public function testGoalStatistics(): void
    {
        $matchEvent = new MatchEvent(
            'goal',
            'Real Madrid',
            14,
            10,
            13,
            [
                'scorer' => 'Vini Junior',
                'assisting_player' => 'Rodrygo Goes',
            ],
            time(),
        );

        $statistics = new MatchStatistics(
            'Real Madrid',
            13,
            ['goals' => 2]
        );

        $statistics->recalculateFromEvent(
            $matchEvent,
            new GoalStatisticCalculator()
        );

        $this->assertEquals($statistics->getData()['goals'], 3);
    }
}
