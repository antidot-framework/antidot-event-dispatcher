<?php

declare(strict_types=1);

namespace Antidot\Event;

use function get_class;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class ListenerProvider implements ListenerProviderInterface, ListenerCollectorInterface
{
    /** @var ListenerCollection */
    private $listenerCollection;

    public function __construct()
    {
        $this->listenerCollection = new ListenerCollection();
    }

    public function addListener(string $eventClass, callable $listener): void
    {
        $this->listenerCollection->addListener($eventClass, $listener);
    }

    /**
     * @param StoppableEventInterface $event
     * @return iterable
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listenerCollection->get(get_class($event));
    }
}
