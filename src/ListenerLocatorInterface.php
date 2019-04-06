<?php

declare(strict_types=1);

namespace Antidot\Event;

interface ListenerLocatorInterface
{
    public function has(string $eventName): bool;
    public function get(string $eventName): iterable;
}
