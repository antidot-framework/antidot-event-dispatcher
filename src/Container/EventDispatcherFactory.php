<?php

declare(strict_types=1);

namespace Antidot\Event\Container;

use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcherFactory
{
    public function __invoke(ContainerInterface $container): EventDispatcherInterface
    {
        $config = $container->get('config')['app-events'];
        $listenerProvider = new ListenerProvider();

        foreach ($config['event-listeners'] ?? [] as $eventClass => $listeners) {
            foreach ($listeners ?? [] as $listenerId) {
                $listenerProvider->addListener(
                    $eventClass,
                    static function () use ($container, $listenerId): callable {
                        return $container->get($listenerId);
                    }
                );
            }
        }

        return new EventDispatcher($listenerProvider);
    }
}
