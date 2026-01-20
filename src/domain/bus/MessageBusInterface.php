<?php

namespace App\domain\bus;

use App\domain\MatchEvent;

interface MessageBusInterface
{
    public function dispatch(MatchEvent $matchEvent);
}