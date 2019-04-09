<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\ListenerInterface;
use Antidot\Event\ListenerProvider;
use AntidotTest\Event\Container\TestEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\StoppableEventInterface;

class ListenerProviderTest extends TestCase
{
    /** @var StoppableEventInterface|MockObject */
    private $event;
    /** @var ListenerInterface[]|MockObject[] */
    private $listeners;
    /** @var ListenerProvider */
    private $provider;

    public function testItShouldAddListenerToCollection(): void
    {
        $this->givenAnEvent();
        $this->givenSomeListeners();
        $this->whenListenersAreAddedToProvider();
        $this->thenProviderHaveTheListenersSubscribedToTheEvent();
    }


    private function givenAnEvent(): void
    {
        $this->event = new TestEvent();
    }

    private function givenSomeListeners(): void
    {
        $this->listeners = [
            $this->createMock(ListenerInterface::class),
            $this->createMock(ListenerInterface::class),
        ];
    }

    private function whenListenersAreAddedToProvider(): void
    {
        $this->provider = new ListenerProvider();
        foreach ($this->listeners as $listener) {
            $this->provider->addListener(TestEvent::class, $listener);
        }
    }

    private function thenProviderHaveTheListenersSubscribedToTheEvent(): void
    {
        $this->assertCount(2, $this->provider->getListenersForEvent($this->event));
    }
}
