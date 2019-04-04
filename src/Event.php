<?php

declare(strict_types=1);

namespace Antidot\Event;

abstract class Event implements EventInterface
{
    /** @var string */
    protected $name;
    /** @var bool */
    protected $stopped;

    public function name(): string
    {
        return $this->name;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }
}
