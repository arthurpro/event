<?php
namespace Graze\Event\Example\Simple;

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Graze\Event\EventDispatcher;
use Graze\Event\EventSubscriberInterface;

class FooSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'event.foo' => 'onFoo'
        );
    }

    public function onFoo()
    {
        echo 'Hello, World!';
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new FooSubscriber());

$dispatcher->dispatch('event.foo'); // Hello, World!
