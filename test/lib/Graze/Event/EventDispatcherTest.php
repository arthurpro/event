<?php
namespace Graze\Event;

use Mockery as m;

class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Graze\Event\EventDispatcherInterface', $this->dispatcher);
    }

    public function testConstructWithSubscribers()
    {
        $subscribers = array(
            m::mock('Graze\Event\EventSubscriberInterface'),
            m::mock('Graze\Event\EventSubscriberInterface')
        );

        $this->assertInstanceOf('Graze\Event\EventDispatcher', new EventDispatcher($subscribers));
    }

    public function testAddSubscriber()
    {
        $subscriber = m::mock('Graze\Event\EventSubscriberInterface');

        $this->assertNull($this->dispatcher->addSubscriber($subscriber));
    }

    public function testAddSubscriberWithPriority()
    {
        $subscriber = m::mock('Graze\Event\EventSubscriberInterface');

        $this->assertNull($this->dispatcher->addSubscriber($subscriber, 255));
    }

    public function testDispatchWithSubscribedSubscribers()
    {
        $foo = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array('event.name' => 'onEventName')));
        $bar = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array('event.name' => 'onEventName')));
        $baz = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array()));

        $foo->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3);

        $bar->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3);

        $baz->shouldReceive('onEventName')
            ->never();

        $dispatcher = new EventDispatcher(array($foo, $bar, $baz));
        $this->assertNull($dispatcher->dispatch('event.name', array(1,2,3)));
    }

    public function testDispatchWithPrioritisedSubscribers()
    {
        $foo = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array('event.name' => 'onEventName')));
        $bar = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array('event.name' => 'onEventName')));
        $baz = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array('event.name' => 'onEventName')));

        $bar->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3)
            ->ordered();

        $baz->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3)
            ->ordered();

        $foo->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3)
            ->ordered();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($foo, -255);
        $dispatcher->addSubscriber($bar, 255);
        $dispatcher->addSubscriber($baz, 0);
        $this->assertNull($dispatcher->dispatch('event.name', array(1,2,3)));
    }

    public function testDispatchWithCallable()
    {
        $object = new \stdClass();
        $object->onEventName = function(){};
        $mock = m::mock($object);

        $foo = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array(
            'event.name' => array($mock, 'onEventName')
        )));

        $mock->shouldReceive('onEventName')
            ->once()
            ->with(1,2,3);;

        $dispatcher = new EventDispatcher(array($foo));
        $this->assertNull($dispatcher->dispatch('event.name', array(1,2,3)));
    }

    public function testDispatchWithClosure()
    {
        $arguments = array();

        $foo = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array(
            'event.name' => function() use (&$arguments) {
                $arguments = func_get_args();
            }
        )));

        $dispatcher = new EventDispatcher(array($foo));
        $dispatcher->dispatch('event.name', array(1,2,3));
        $this->assertSame(array(1,2,3), $arguments);
    }

    public function testDispatchWithNoSubscribedSubscribers()
    {
        $foo = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array()));
        $bar = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array()));
        $baz = m::mock('Graze\Event\EventSubscriberInterface', array('getSubscribedEvents' => array()));

        $foo->shouldReceive('onEventName')
            ->never();

        $bar->shouldReceive('onEventName')
            ->never();

        $baz->shouldReceive('onEventName')
            ->never();

        $dispatcher = new EventDispatcher(array($foo, $bar, $baz));
        $this->assertNull($dispatcher->dispatch('event.name', array(1,2,3)));
    }
}
