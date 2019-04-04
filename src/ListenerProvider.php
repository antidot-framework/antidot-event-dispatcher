<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface, ListenerCollectorInterface
{
    /** @var ListenerCollection */
    private $listenerCollection;

    public function __construct()
    {
        $this->listenerCollection = new ListenerCollection();
    }

    public function addListener(string $eventName, callable $listener): void
    {
        $this->listenerCollection->addListener($eventName, $listener);
    }

    /**
     * @param EventInterface $event
     * @return iterable
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listenerCollection->get($event->name());
    }
}
