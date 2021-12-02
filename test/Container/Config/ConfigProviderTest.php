<?php

namespace AntidotTest\Event\Container\Config;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\EventDispatcherFactory;
use Antidot\Event\Container\ListenerProviderFactory;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class ConfigProviderTest extends TestCase
{
    public function testItShouldREturnDefaultConfig(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertSame([
            'factories' => [
                EventDispatcherInterface::class => EventDispatcherFactory::class,
                ListenerProviderInterface::class => ListenerProviderFactory::class,
            ],
            'app-events' => []
        ], $configProvider());
    }
}
