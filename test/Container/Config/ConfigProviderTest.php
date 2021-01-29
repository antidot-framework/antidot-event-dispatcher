<?php

namespace AntidotTest\Event\Container\Config;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\EventDispatcherFactory;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class ConfigProviderTest extends TestCase
{
    public function testItShouldREturnDefaultConfig(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertSame([
            'factories' => [
                EventDispatcherInterface::class => EventDispatcherFactory::class,
            ],
            'app-events' => []
        ], $configProvider());
    }
}
