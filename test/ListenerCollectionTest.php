<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\EventInterface;
use Antidot\Event\ListenerCollection;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListenerCollectionTest extends TestCase
{
    /** @var EventInterface|MockObject */
    private $event;
    /** @var ListenerInterface|MockObject */
    private $listeners;
    /** @var ListenerCollection */
    private $collection;

    public function testItShouldAddListenersLoCollection(): void
    {
        $this->givenAnEvent();
        $this->givenSomeListeners();
        $this->havingACollectionWithoutAnyListeners();
        $this->whenListenerIsAddedToCollection();
        $this->thenCollectionShouldHaveAListenerSubscribedToEvent();
    }

    private function givenAnEvent(): void
    {
        $this->event = $this->createMock(EventInterface::class);
    }

    private function givenSomeListeners(): void
    {
        $this->listeners = [
            $this->createMock(ListenerInterface::class),
            $this->createMock(ListenerInterface::class),
        ];
    }

    private function havingACollectionWithoutAnyListeners(): void
    {
        $this->collection = new ListenerCollection();
        $this->assertCount(0, $this->collection);
    }

    private function whenListenerIsAddedToCollection(): void
    {
        foreach ($this->listeners as $listener) {
            $this->collection->addListener($this->event->name(), $listener);
        }
    }

    private function thenCollectionShouldHaveAListenerSubscribedToEvent(): void
    {
        $this->assertCount(1, $this->collection);

        foreach ($this->collection->get($this->event->name()) as $listener) {
            $this->assertInstanceOf(ListenerInterface::class, $listener);
        }
    }
}
