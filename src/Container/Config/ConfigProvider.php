<?php

declare(strict_types=1);

namespace Antidot\Event\Container\Config;

use Antidot\Event\Container\EventDispatcherFactory;
use Psr\EventDispatcher\EventDispatcherInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'factories' => [
                    EventDispatcherInterface::class => EventDispatcherFactory::class,
                ]
            ],
            'app-events' => [
//                'event.name' => [
//                    'Listener1',
//                    'Listener2',
//                    ...
//                ]
            ]
        ];
    }
}
