<?php

namespace App\domain;

interface MatchEventHandlerInterface
{
    public function supportsEvent(MatchEvent $event): bool;

    public function handleEvent(MatchEvent $event);
}