<?php

declare(strict_types=1);

namespace Antidot\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface ListenerInterface
{
    /**
     * @param object|StoppableEventInterface $event
     * @return object|StoppableEventInterface
     */
    public function __invoke(object $event): object;
}
