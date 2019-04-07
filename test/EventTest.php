<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private const NAME = 'some.event';
    private $name;
    /** @var Event */
    private $eventClass;
    /** @var Event */
    private $event;

    public function testItShouldGetName(): void
    {
        $this->givenAName();
        $this->havingAnEvent();
        $this->whenEventOccurred();
        $this->thenEventHaveACorrectName();
        $this->andThenEventCanPropagate();
    }

    public function testItShouldBeStoppedEvent(): void
    {
        $this->givenAName();
        $this->havingAnEvent();
        $this->whenStoppedEventOccurred();
        $this->thenEventHasStoppedPropagation();
    }

    private function givenAName(): void
    {
        $this->name = self::NAME;
    }

    private function havingAnEvent(): void
    {
        $this->eventClass = new class extends Event {
            public static function occur(string $name, bool $stopped = false): self
            {
                $self = new self;
                $self->name = $name;
                $self->stopped = $stopped;

                return $self;
            }
        };
    }

    private function whenEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur(self::NAME);
    }

    private function thenEventHaveACorrectName(): void
    {
        $this->assertEquals(self::NAME, $this->event->name());
    }

    private function whenStoppedEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur(self::NAME, true);
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
