<?php

declare(strict_types=1);

namespace Antidot\Event\Container\Config;

use Antidot\Event\Container\EventDispatcherFactory;
use Antidot\Event\Container\ListenerProviderFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'factories' => [
                EventDispatcherInterface::class => EventDispatcherFactory::class,
                ListenerProviderInterface::class => ListenerProviderFactory::class,
            ],
            'app-events' => [
//                'event-listeners' => [
//                    'event.name' => [
//                        'Listener1',
//                        'Listener2',
//                        ...
//                    ]
//                ]
            ]
        ];
    }
}
