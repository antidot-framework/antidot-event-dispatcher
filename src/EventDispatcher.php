<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var ListenerProvider */
    private $listenerProvider;

    public function __construct(ListenerProvider $listenerProvider)
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
        foreach ($listeners as $callableListener) {
            if ($event->isPropagationStopped()) {
                return $event;
            }
            $listener = $callableListener();
            $listener($event);
        }

        return $event;
    }
}
