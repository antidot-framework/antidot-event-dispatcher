<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventInterface extends StoppableEventInterface
{
    public function name(): string;
}
