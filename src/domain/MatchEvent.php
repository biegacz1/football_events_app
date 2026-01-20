<?php

namespace App\domain;

final readonly class MatchEvent
{
    public string $type;
    public string $teamId;
    public int $minute;
    public int $second;
    public int $matchId;
    public int $timestamp;
    public array $eventData;

    public function __construct(
        string $type,
        string $teamId,
        string $minute,
        string $second,
        string $matchId,
        array $eventData,
        int $timestamp,
    ) {
        $this->type = $type;
        $this->teamId = $teamId;
        $this->minute = $minute;
        $this->second = $second;
        $this->matchId = $matchId;
        $this->eventData = $eventData;
        $this->timestamp = $timestamp;
    }

    public static function fromArray(array $data, int $timestamp): self
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('Event type is required');
        }

        if (!isset($data['team_id']) || !isset($data['match_id'])) {
            throw new \InvalidArgumentException('match_id and team_id are required for foul events');
        }

        return new self(
            $data['type'],
            $data['team_id'],
            $data['minute'],
            $data['second'],
            $data['match_id'],
            $data['event_data'],
            $timestamp,
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'team_id' => $this->teamId,
            'match_id' => $this->matchId,
            'minute' => $this->minute,
            'second' => $this->second,
            'event_data' => $this->eventData,
        ];
    }
}