<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private $listenerProvider;

    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * @param EventInterface $event
     * @return object
     */
    public function dispatch(object $event): object
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);
        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                return $event;
            }
            $listener($event);
        }

        return $event;
    }
}
