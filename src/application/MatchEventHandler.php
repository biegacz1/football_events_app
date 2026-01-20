<?php

namespace App\application;

use App\domain\bus\ConsumerInterface;
use App\domain\MatchEvent;
use App\domain\MatchEventHandlerInterface;
use App\domain\PublisherInterface;
use App\domain\utils\TransactionalInterface;

class MatchEventHandler implements ConsumerInterface
{
    public function __construct(
        private array $handlers,
        private PublisherInterface $publisher,
        private TransactionalInterface $transactional
    ) {}

    public function consume(MatchEvent $matchEvent): void
    {
        $this->transactional->startTransaction();

        try {
            $this->handleEvent($matchEvent);
            $this->transactional->commit();
        } catch (\Exception) {
            $this->transactional->rollback();
        }

        $this->publisher->publishNotification($matchEvent);
    }

    public function handleEvent(MatchEvent $matchEvent): void
    {
        /** @var MatchEventHandlerInterface $handler */
        foreach ($this->handlers as $handler) {
            if ($handler->supportsEvent($matchEvent)) {
                $handler->handleEvent($matchEvent);
                return;
            }
        }
    }
}