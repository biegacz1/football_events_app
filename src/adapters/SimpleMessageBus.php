<?php

namespace App\adapters;

use App\domain\bus\ConsumerInterface;
use App\domain\bus\MessageBusInterface;
use App\domain\MatchEvent;

class SimpleMessageBus implements MessageBusInterface
{
    public function __construct(
        private array $consumers
    ) {
    }

    public function dispatch(MatchEvent $matchEvent): void
    {
        /** @var ConsumerInterface $consumer */
        foreach ($this->consumers as $consumer) {
            $consumer->consume($matchEvent);
        }
    }
}