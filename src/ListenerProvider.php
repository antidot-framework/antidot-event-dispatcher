<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use function get_class;

class ListenerProvider implements ListenerProviderInterface, ListenerCollectorInterface
{
    private ListenerCollection $listenerCollection;

    public function __construct()
    {
        $this->listenerCollection = new ListenerCollection();
    }

    public function addListener(string $eventClass, callable $listener): void
    {
        $this->listenerCollection->addListener($eventClass, $listener);
    }

    /**
     * @param StoppableEventInterface|object $event
     * @return iterable<mixed, mixed>
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listenerCollection->get(get_class($event)) as $listenerFactory) {
            yield $listenerFactory();
        }
    }
}
