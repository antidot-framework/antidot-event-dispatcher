<?php

declare(strict_types=1);

namespace AntidotTest\Event\Container;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\EventDispatcherFactory;
use Antidot\Event\Event;
use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerInterface;
use function dump;
use function get_class;
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
        $this->havingListenersConfiguredToReceiveAnEvent();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
        $this->andThenDispatcherShouldDispatchAnEvent();
    }

    public function testItShouldCreateNewInstancesOfEventDispatcherAlsoWithIncompleteConfiguration(): void
    {
        $this->givenAnIncompleteConfigProvider();
        $this->havingAContainerWithConfig();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
    }

    public function testItShouldCreateNewInstancesOfEventDispatcherAlsoWithMoreIncompleteConfiguration(): void
    {
        $this->givenAMoreIncompleteConfigProvider();
        $this->havingAContainerWithConfig();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
    }

    public function testItShouldThrowAnExceptionWhenAnInvalidConfigGiven(): void
    {
        $this->expectAnException();
        $this->givenInvalidConfiguration();
        $this->havingAContainerWithConfig();
        $this->whenEventDispatcherFactoryIsInvoked();
    }

    private function givenAConfigProvider(): void
    {
        $config = new ConfigProvider();
        $this->config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => [
                    TestEvent::class => [
                        'Listener1',
                        'Listener2',
                    ]
                ]
            ]
        ]);
    }

    private function givenInvalidConfiguration(): void
    {
        $this->config = [
            'other-configs' => [
                'event-listeners' => [
                    TestEvent::class => [
                        'Listener1',
                        'Listener2',
                    ]
                ]
            ]
        ];
    }

    private function givenAnIncompleteConfigProvider(): void
    {
        $config = new ConfigProvider();
        $this->config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => [
                    TestEvent::class => null
                ]
            ]
        ]);
    }

    private function givenAMoreIncompleteConfigProvider(): void
    {
        $config = new ConfigProvider();
        $this->config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => null
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
    }

    private function havingListenersConfiguredToReceiveAnEvent(): void
    {
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
        $event = new TestEvent();

        $this->dispatcher->dispatch($event);
    }

    private function expectAnException(): void
    {
        $this->expectException(\RuntimeException::class);
    }
}
