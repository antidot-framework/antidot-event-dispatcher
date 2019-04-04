<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\EventInterface;
use Antidot\Event\ListenerCollection;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\TestCase;

class ListenerCollectionTest extends TestCase
{
    /** @var EventInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $event;
    /** @var ListenerInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $listener;
    /** @var ListenerCollection */
    private $collection;

    public function testItShouldAddListenersLoCollection(): void
    {
        $this->givenAnEvent();
        $this->givenAListener();
        $this->havingACollectionWithoutAnyListeners();
        $this->whenListenerIsAddedToCollection();
        $this->thenCollectionShouldHaveAListenerSubscribedToEvent();
    }

    private function givenAnEvent(): void
    {
        $this->event = $this->createMock(EventInterface::class);
    }

    private function givenAListener(): void
    {
        $this->listener = $this->createMock(ListenerInterface::class);
    }

    private function havingACollectionWithoutAnyListeners(): void
    {
        $this->collection = new ListenerCollection();
        $this->assertCount(0, $this->collection);
    }

    private function whenListenerIsAddedToCollection(): void
    {
        $this->collection->addListener($this->event->name(), $this->listener);
    }

    private function thenCollectionShouldHaveAListenerSubscribedToEvent(): void
    {
        $this->assertCount(1, $this->collection);

        foreach ($this->collection->get($this->event->name()) as $listener) {
            $this->assertInstanceOf(ListenerInterface::class, $listener);
        }
    }
}
