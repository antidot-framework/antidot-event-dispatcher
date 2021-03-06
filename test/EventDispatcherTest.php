<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcherTest extends TestCase
{
    /** @var StoppableEventInterface|MockObject */
    private $event;
    /** @var ListenerProviderInterface|MockObject */
    private $listenerProvider;
    /** @var ListenerInterface|MockObject */
    private $listener1;
    /** @var ListenerInterface|MockObject */
    private $listener2;

    public function testItShouldDispatchEvents(): void
    {
        $this->givenAnEvent();
        $this->havingAListenerProvider();
        $this->havingListenersForEventInListenerProvider();
        $this->eventIsHandledByConfiguredListenersExpectsBeCalled(2);
        $this->whenEventIsDispatched();
    }

    public function testItShouldDispatchEventOfAnyTypeOfObject(): void
    {
        $this->givenANotStoppableEvent();
        $this->havingAListenerProvider();
        $this->havingListenersForEventInListenerProvider();
        $this->whenEventIsDispatched();
    }

    public function testItShouldNotHandleAnyEventWhenPropagationIsStopped(): void
    {
        $this->givenAnEvent();
        $this->givenEventHasStoppedPropagation();
        $this->havingAListenerProvider();
        $this->havingListenersForStoppedEventInListenerProvider();
        $this->eventIsHandledByConfiguredListenersExpectsBeCalled(1);
        $this->whenEventIsDispatched();
    }

    private function givenAnEvent(): void
    {
        $this->event = $this->createMock(StoppableEventInterface::class);
    }

    private function givenANotStoppableEvent(): void
    {
        $this->event = $this->createMock(\StdClass::class);
    }

    private function havingAListenerProvider(): void
    {
        $this->listenerProvider = $this->createMock(ListenerProviderInterface::class);
    }

    private function havingListenersForEventInListenerProvider(): void
    {
        $this->listener1 = $this->makeListener(1);
        $this->listener2 = $this->makeListener(1);

        $this->listenerProvider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($this->event)
            ->willReturn([
                $this->listener1,
                $this->listener2,
            ]);
    }
    private function havingListenersForStoppedEventInListenerProvider(): void
    {
        $this->listener1 = $this->makeListener(0);
        $this->listener2 = $this->makeListener(0);

        $this->listenerProvider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($this->event)
            ->willReturn([
                $this->listener1,
                $this->listener2,
            ]);
    }


    private function eventIsHandledByConfiguredListenersExpectsBeCalled(int $callTimes): void
    {
        $this->event
            ->expects($this->exactly($callTimes))
            ->method('isPropagationStopped');
    }

    private function whenEventIsDispatched(): void
    {
        $eventDispatcher = new EventDispatcher($this->listenerProvider);
        $eventDispatcher->dispatch($this->event);
    }

    private function makeListener(int $callTimes): ListenerInterface
    {
        $listener = $this->createMock(ListenerInterface::class);
        $listener
            ->expects($this->exactly($callTimes))
            ->method('__invoke')
            ->with($this->event);

        return $listener;
    }

    private function givenEventHasStoppedPropagation(): void
    {
        $this->event
            ->method('isPropagationStopped')
            ->willReturn(true);
    }
}
