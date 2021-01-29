<?php

declare(strict_types=1);

namespace AntidotTest\Event\Container;

use Antidot\Event\Container\Config\ConfigProvider;
use Antidot\Event\Container\EventDispatcherFactory;
use Antidot\Event\EventDispatcher;
use Antidot\Event\ListenerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use RuntimeException;
use function array_merge;

class EventDispatcherFactoryTest extends TestCase
{
    /** @var array */
    private $config;
    /** @var ContainerInterface|MockObject */
    private $container;
    /** @var EventDispatcherInterface */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testItShouldThrowExceptionGievenEmptyConfig(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'Something went wrong constructing an instance of %s, review config related to \'app-events\''
            . ' and see the previous exception for more info.',
            EventDispatcher::class
        ));
        $this->expectExceptionCode(0);
        $this->container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([]);
        $this->whenEventDispatcherFactoryIsInvoked();
    }

    public function testItShouldCreateNewInstancesOfEventDispatcher(): void
    {
        $this->givenAConfigProvider();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
        $this->andThenDispatcherShouldDispatchAnEvent();
    }

    public function testItShouldCreateNewInstancesOfEventDispatcherAlsoWithIncompleteConfiguration(): void
    {
        $this->givenAnIncompleteConfigProvider();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
    }

    public function testItShouldCreateNewInstancesOfEventDispatcherAlsoWithMoreIncompleteConfiguration(): void
    {
        $this->givenAMoreIncompleteConfigProvider();
        $this->whenEventDispatcherFactoryIsInvoked();
        $this->thenItReturnsInstanceOfEventDispatcher();
    }

    public function testItShouldThrowAnExceptionWhenAnInvalidConfigGiven(): void
    {
        $this->expectAnException();
        $this->givenInvalidConfiguration();
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
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container
            ->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(['config'], ['Listener1'], ['Listener2'])
            ->willReturnOnConsecutiveCalls(
                $this->config,
                $this->createMock(ListenerInterface::class),
                $this->createMock(ListenerInterface::class)
            );
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
        $this->prepareContainer();
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
        $this->prepareContainer();
    }

    private function givenAMoreIncompleteConfigProvider(): void
    {
        $config = new ConfigProvider();
        $this->config = array_merge($config->__invoke(), [
            'app-events' => [
                'event-listeners' => null
            ]
        ]);
        $this->prepareContainer();
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
        $this->expectException(RuntimeException::class);
    }

    private function prepareContainer(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($this->config);
    }
}
