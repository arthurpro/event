<?php
namespace Graze\Event\Example\Callable;

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Graze\Event\EventDispatcher;
use Graze\Event\EventSubscriberInterface;

class FooBehaviour
{
    public function onFoo($who)
    {
        echo "Hello, $who!";
    }
}

class FooSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'event.foo' => array('Graze\Event\Example\Callable\FooBehaviour', 'onFoo')
        );
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new FooSubscriber());

$dispatcher->dispatch('event.foo', array('World')); // Hello, World!
