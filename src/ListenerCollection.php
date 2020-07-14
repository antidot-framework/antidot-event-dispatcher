<?php

declare(strict_types=1);

namespace Antidot\Event;

use IteratorAggregate;

use function array_key_exists;

/**
 * @implements \IteratorAggregate<mixed>
 */
class ListenerCollection implements IteratorAggregate, ListenerCollectorInterface, ListenerLocatorInterface
{
    /** @var array<string, array<int, callable>> */
    private array $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    public function addListener(string $eventClass, callable $listener): void
    {
        if ($this->has($eventClass)) {
            $this->listeners[$eventClass][] = $listener;
            return;
        }

        $this->listeners[$eventClass] = [$listener];
    }

    /**
     * @return iterable<callable>
     */
    public function get(string $eventClass): iterable
    {
        if ($this->has($eventClass)) {
            yield from $this->listeners[$eventClass];
        }
    }

    public function has(string $eventClass): bool
    {
        return array_key_exists($eventClass, $this->listeners);
    }

    /**
     * @return \Generator<mixed>|\Traversable<mixed>
     */
    public function getIterator()
    {
        yield from $this->listeners;
    }
}
