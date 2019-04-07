# Antidot event dispatcher

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/kpicaza/antidot-event-dispatcher/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Maintainability](https://api.codeclimate.com/v1/badges/6568ab3621bae2850e6d/maintainability)](https://codeclimate.com/github/kpicaza/antidot-event-dispatcher/maintainability)

[Psr 14 Event dispatcher](https://github.com/php-fig/event-dispatcher) implementation.

## Installation

Using [composer](https://getcomposer.org/download/)

````
composer require antidot-fw/event-dispatcher
````

### Using [Zend Config Aggregator](https://framework.zend.com/blog/2017-04-20-config-aggregator.html)
it install the library automatically

![install](./docs/install.jpg)

### Using factory:

#### Config

````php
<?php
/** @var \Psr\Container\ContainerInterface $container */
$container->set('config', [
    'app-events' => [
        'event-listeners' => [
//            SomeEvent::class => [
            'some.event' => [
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
