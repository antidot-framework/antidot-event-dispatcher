# Event Dispatcher

[Psr 14 Event dispatcher](https://github.com/php-fig/event-dispatcher) implementation.

## Installation

Using [composer](https://getcomposer.org/download/)

````
composer require antidot-fw/event-dispatcher
````

### Using Zend Config Aggregator

[Zend config Aggregator](https://framework.zend.com/blog/2017-04-20-config-aggregator.html) installs the library automatically

![install](/install.jpg)

### Using factory:

#### Config

````php
<?php
/** @var \Psr\Container\ContainerInterface $container */
$container->set('config', [
    'app-events' => [
        'event-listeners' => [
            SomeEvent::class => [
                SomeEventListener::class,
                SomeEventOtherListener::class,
            ]
        ]
    ]
]);
````
#### factory

````php
<?php

use Antidot\Event\Container\EventDispatcherFactory;
use Psr\EventDispatcher\EventDispatcherInterface;

$factory = new EventDispatcherFactory();

$eventDispatcher = $factory->__invoke($container);
$container->set(EventDispatcherInterface::class, $eventDispatcher);
````

## Usage

### Send events

````php
<?php

use Psr\EventDispatcher\EventDispatcherInterface;

/** @var \Psr\Container\ContainerInterface $container */
$eventDispatcher = $container->get(EventDispatcherInterface::class);

$eventDispatcher->dispatch(SomeEvent::occur());

````
