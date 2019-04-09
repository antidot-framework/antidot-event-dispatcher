<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface ListenerInterface
{
    public function __invoke(StoppableEventInterface $event): StoppableEventInterface;
}
