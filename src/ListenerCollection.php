<?php

declare(strict_types=1);

namespace Antidot\Event;

use IteratorAggregate;
use Psr\Container\ContainerInterface;

use function array_key_exists;

class ListenerCollection implements IteratorAggregate, ListenerCollectorInterface, ListenerLocatorInterface
{
    /** @var array<string, array<int, callable>> */
    private $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    public function addListener(string $eventName, callable $listener): void
    {
        if ($this->has($eventName)) {
            $this->listeners[$eventName][] = $listener;
            return;
        }

        $this->listeners[$eventName] = [$listener];
    }

    public function get(string $eventName): iterable
    {
        if ($this->has($eventName)) {
            yield from $this->listeners[$eventName];
        }
    }

    public function has(string $eventName): bool
    {
        return array_key_exists($eventName, $this->listeners);
    }

    public function getIterator()
    {
        yield from $this->listeners;
    }
}
