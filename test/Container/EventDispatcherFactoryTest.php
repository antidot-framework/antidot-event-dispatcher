<?php

declare(strict_types=1);

namespace AntidotTest\Event\Container;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\EventDispatcherFactory;
use Antidot\Event\Event;
use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function array_merge;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcherFactoryTest extends TestCase
{
    /** @var array */
    private $config;
    /** @var ContainerInterface|MockObject */
    private $container;
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function testItShouldCreateNewInstancesOfEventDispatcher(): void
    {
        $this->givenAConfigProvider();
        $this->havingAContainerWithConfig();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
        $this->andThenDispatcherShouldDispatchAnEvent();
    }

    private function givenAConfigProvider(): void
    {
        $config = new ConfigProvider();
        $this->config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => [
                    'event.name' => [
                        'Listener1',
                        'Listener2',
                    ]
                ]
            ]
        ]);
    }

    private function havingAContainerWithConfig(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with('config')
            ->willReturn($this->config);
        $this->container
            ->expects($this->at(1))
            ->method('get')
            ->with('Listener1')
            ->willReturn($this->createMock(ListenerInterface::class));
        $this->container
            ->expects($this->at(2))
            ->method('get')
            ->with('Listener2')
            ->willReturn($this->createMock(ListenerInterface::class));
    }

    private function whenEventDispatcherFactoryIsInvoked(): void
    {
        $factory = new EventDispatcherFactory();
        $this->dispatcher = $factory($this->container);
    }

    private function thenItReturnsInstanceOfEventDispatcher(): void
    {
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    private function andThenDispatcherShouldDispatchAnEvent(): void
    {
        $event = new class extends Event {
            protected $name = 'event.name';
            protected $stopped = false;
        };

        $this->dispatcher->dispatch($event);
    }
}
