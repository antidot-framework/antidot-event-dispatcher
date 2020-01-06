<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
    protected bool $stopped;

    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }
}
