<?php

declare(strict_types=1);

namespace Antidot\Event\Container;

use Antidot\Event\AsyncEventDispatcher;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use React\EventLoop\Loop;

final class AsyncEventDispatcherFactory
{
    public function __invoke(ContainerInterface $container): EventDispatcherInterface
    {
        /** @var ListenerProviderInterface $listenerProvider */
        $listenerProvider = $container->get(ListenerProviderInterface::class);

        return new AsyncEventDispatcher($listenerProvider, Loop::get());
    }
}
