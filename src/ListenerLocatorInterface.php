<?php

declare(strict_types=1);

namespace Antidot\Event;

interface ListenerLocatorInterface
{
    public function has(string $eventClass): bool;
    public function get(string $eventClass): iterable;
}
