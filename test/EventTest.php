<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /** @var Event */
    private $eventClass;
    /** @var Event */
    private $event;

    public function testItShouldGetName(): void
    {
        $this->havingAnEvent();
        $this->whenEventOccurred();
        $this->thenEventHaveACorrectName();
        $this->andThenEventCanPropagate();
    }

    public function testItShouldBeStoppedEvent(): void
    {
        $this->havingAnEvent();
        $this->whenStoppedEventOccurred();
        $this->thenEventHasStoppedPropagation();
    }

    private function havingAnEvent(): void
    {
        $this->eventClass = new class extends Event {
            public static function occur(bool $stopped = false): self
            {
                $self = new self;
                $self->stopped = $stopped;

                return $self;
            }
        };
    }

    private function whenEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur();
    }

    private function thenEventHaveACorrectName(): void
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }

    private function whenStoppedEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur(true);
    }

    private function thenEventHasStoppedPropagation(): void
    {
        $this->assertTrue($this->event->isPropagationStopped());
    }

    private function andThenEventCanPropagate(): void
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }
}
