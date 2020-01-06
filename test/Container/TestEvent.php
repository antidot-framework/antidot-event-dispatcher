<?php

declare(strict_types=1);

namespace AntidotTest\Event\Container;

use Antidot\Event\Event;

class TestEvent extends Event
{
    protected bool $stopped = false;
}
