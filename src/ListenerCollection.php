<?php

declare(strict_types=1);

namespace Antidot\Event;

use InvalidArgumentException;
use IteratorAggregate;
use Psr\Container\ContainerInterface;

use function array_key_exists;

class ListenerCollection implements IteratorAggregate, ContainerInterface, ListenerCollectorInterface
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

    public function get($id): iterable
    {
        if ($this->has($id)) {
            return $this->listeners[$id];
        }

        throw new InvalidArgumentException('Invalid Event identifier given.');
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->listeners);
    }

    public function getIterator()
    {
        yield from $this->listeners;
    }
}
