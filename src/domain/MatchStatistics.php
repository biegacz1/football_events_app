<?php

namespace App\domain;

final class MatchStatistics
{
    private string $team;
    private int $matchId;
    private array $data;

    public function __construct(
        string $team,
        int $matchId,
        $data = [],
    ) {
        $this->team = $team;
        $this->matchId = $matchId;
        $this->data = $data;
    }

    public function recalculateFromEvent(MatchEvent $event, StatisticCalculatorInterface $updater): void
    {
        $this->data = $updater->recalculateStatistics($this->data, $event);
    }

    public static function fromEvent(MatchEvent $event, array $data = []): self
    {
        return new self(
            $event->teamId,
            $event->matchId,
            $data,
        );
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getMatchId(): int
    {
        return $this->matchId;
    }
}