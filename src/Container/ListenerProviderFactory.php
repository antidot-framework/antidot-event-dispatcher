<?php

declare(strict_types=1);

namespace Antidot\Event\Container;

use Antidot\Event\ListenerProvider;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ListenerProviderFactory
{
    public function __invoke(ContainerInterface $container): ListenerProviderInterface
    {
        /** @var array<string, mixed> $globalConfig */
        $globalConfig = $container->get('config');
        /** @var array<string, array> $config */
        $config = $globalConfig['app-events'];
        $listenerProvider = new ListenerProvider();
        /**
         * @var string $eventClass
         * @var ?array<string> $listeners
         */
        foreach ($config['event-listeners'] ?? [] as $eventClass => $listeners) {
            foreach ($listeners ?? [] as $listenerId) {
                $listenerProvider->addListener(
                    $eventClass,
                    static function () use ($container, $listenerId): callable {
                        /** @var callable $listener */
                        $listener = $container->get($listenerId);

                        return $listener;
                    }
                );
            }
        }

        return $listenerProvider;
    }
}
