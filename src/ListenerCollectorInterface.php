<?php

declare(strict_types=1);

namespace Antidot\Event;

interface ListenerCollectorInterface
{
    public function addListener(string $eventClass, callable $listener): void;
}
