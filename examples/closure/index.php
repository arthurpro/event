<?php
namespace Graze\Event\Example\Closure;

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Graze\Event\EventDispatcher;
use Graze\Event\EventSubscriberInterface;

class FooSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        $self = $this;

        return array(
            'event.foo' => function($who = null) use ($self) {
                if ($who) {
                    $self->onFoo($who);
                }
            }
        );
    }

    public function onFoo($who)
    {
        echo "Hello, $who!";
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new FooSubscriber());

$dispatcher->dispatch('event.foo', array('World')); // Hello, World!
