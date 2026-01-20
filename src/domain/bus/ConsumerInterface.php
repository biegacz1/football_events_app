<?php

namespace App\domain\bus;

use App\domain\MatchEvent;

interface ConsumerInterface
{
    public function consume(MatchEvent $matchEvent);
}