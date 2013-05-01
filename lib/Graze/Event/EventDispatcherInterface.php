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
interface EventDispatcherInterface
{
    /**
     * @param string $event
     * @param array $arguments
     */
    public function dispatch($event, array $arguments = array());

    /**
     * @param EventSubscriberInterface $subscriber
     * @param integer $priority
     */
    public function addSubscriber(EventSubscriberInterface $subscriber, $priority = 0);
}
