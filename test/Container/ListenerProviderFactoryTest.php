<?php

namespace Antidot\Test\Event\Container;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\ListenerProviderFactory;
use Antidot\Event\ListenerInterface;
use AntidotTest\Event\Container\TestEvent;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ListenerProviderFactoryTest extends TestCase
{
    public function testItShouldVConfigureListenerProviderFactory(): void
    {
        $config = new ConfigProvider();
        $config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => [
                    TestEvent::class => [
                        'Listener1',
                        'Listener2',
                    ]
                ]
            ]
        ]);
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(['config'], ['Listener1'], ['Listener2'])
            ->willReturnOnConsecutiveCalls(
                $config,
                $this->createMock(ListenerInterface::class),
                $this->createMock(ListenerInterface::class)
            );


        $listenerProviderFactory = new ListenerProviderFactory();
        $listenerProvider = $listenerProviderFactory->__invoke($container);
        self::assertCount(2, $listenerProvider->getListenersForEvent(new TestEvent()));
    }
}
