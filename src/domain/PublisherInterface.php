<?php

namespace App\domain;

interface PublisherInterface
{
    /** Publishes events to frontend */
    public function publishNotification(MatchEvent $event): void;
}