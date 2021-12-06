<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use React\EventLoop\LoopInterface;

final class AsyncEventDispatcher implements EventDispatcherInterface
{
    private ListenerProviderInterface $listenerProvider;
    private LoopInterface $loop;

    public function __construct(ListenerProviderInterface $listenerProvider, LoopInterface $loop)
    {
        $this->listenerProvider = $listenerProvider;
        $this->loop = $loop;
    }

    /**
     * @param StoppableEventInterface|object $event
     * @return object
     */
    public function dispatch(object $event): object
    {
        $this->loop->futureTick(function () use ($event) {
            /** @var callable[] $listeners */
            $listeners = $this->listenerProvider->getListenersForEvent($event);
            foreach ($listeners as $listener) {
                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    return;
                }
                $listener($event);
            }
        });


        return $event;
    }
}
