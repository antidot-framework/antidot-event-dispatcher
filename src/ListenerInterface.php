<?php

declare(strict_types=1);

namespace Antidot\Event;

interface ListenerInterface
{
    public function __invoke(EventInterface $event): EventInterface;
}
