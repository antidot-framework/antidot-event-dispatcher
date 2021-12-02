<?php

namespace Antidot\Test\Event;

use Antidot\Event\AsyncEventDispatcher;
use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use React\EventLoop\Loop;
use StdClass;

class AsyncEventDispatcherTest extends TestCase
{
    public function testItShouldDispatchEvents(): void
    {
        $event = $this->createMock(StoppableEventInterface::class);
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);

        $listenerProvider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([
                $this->makeListener(1, $event),
                $this->makeListener(1, $event),
            ]);

        $event
            ->expects($this->exactly(2))
            ->method('isPropagationStopped');

        $eventDispatcher = new AsyncEventDispatcher($listenerProvider, Loop::get());
        $eventDispatcher->dispatch($event);

        Loop::run();
    }

    public function testItShouldDispatchEventOfAnyTypeOfObject(): void
    {
        $event = $this->createMock(StdClass::class);
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);

        $listenerProvider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([
                $this->makeListener(1, $event),
                $this->makeListener(1, $event),
            ]);

        $eventDispatcher = new AsyncEventDispatcher($listenerProvider, Loop::get());
        $eventDispatcher->dispatch($event);

        Loop::run();
    }

    public function testItShouldNotHandleAnyEventWhenPropagationIsStopped(): void
    {
        $event = $this->createMock(StoppableEventInterface::class);
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);

        $listenerProvider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([
                $this->makeListener(1, $event),
                $this->makeListener(0, $event),
            ]);

        $event
            ->expects($this->exactly(2))
            ->method('isPropagationStopped')
            ->willReturnOnConsecutiveCalls(
                false,
                true
            );

        $eventDispatcher = new AsyncEventDispatcher($listenerProvider, Loop::get());
        $eventDispatcher->dispatch($event);

        Loop::run();
    }

    private function makeListener(int $callTimes, $event): ListenerInterface
    {
        $listener = $this->createMock(ListenerInterface::class);
        $listener
            ->expects($this->exactly($callTimes))
            ->method('__invoke')
            ->with($event);

        return $listener;
    }
}
