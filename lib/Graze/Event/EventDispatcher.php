<?php
namespace Graze\Event;

/**
 * This file is part of Graze\Event
 *
 * Copyright (c) 2013 Nature Delivered Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrew Lawson <andrew.lawson@graze.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var integer
     */
    protected $order = PHP_INT_MAX;

    /**
     * @var SplPriorityQueue
     */
    protected $subscribers;

    /**
     * @param array $subscribers
     */
    public function __construct(array $subscribers = array())
    {
        $this->subscribers = new \SplPriorityQueue();

        foreach ($subscribers as $subscriber) {
            $this->addSubscriber($subscriber);
        }
    }

    /**
     * @param string $event
     * @param array $arguments
     */
    public function dispatch($event, array $arguments = array())
    {
        foreach (clone $this->subscribers as $subscriber) {
            $method = $this->getSubscribedMethod($event, $subscriber);

            if (is_callable($method)) {
                call_user_func_array($method, $arguments);
            } elseif (is_string($method)) {
                call_user_func_array(array($subscriber, $method), $arguments);
            }
        }
    }

    /**
     * @param EventSubscriberInterface $subscriber
     * @param integer $priority
     */
    public function addSubscriber(EventSubscriberInterface $subscriber, $priority = 0)
    {
        $this->subscribers->insert($subscriber, array((integer) $priority, --$this->order));
    }

    /**
     * @param string $event
     * @param EventSubscriberInterface $subscriber
     * @return string
     */
    protected function getSubscribedMethod($event, EventSubscriberInterface $subscriber)
    {
        $events = $subscriber->getSubscribedEvents();

        return isset($events[$event]) ? $events[$event] : null;
    }
}
