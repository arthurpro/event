# Graze\Event #


**Version:** *0.1.0*<br/>
**Master build:** [![Master branch build status][travis-master]][travis]


Event is a simple event pub/sub library for PHP. It can be installed in whichever
way you prefer, but we recommend [Composer][composer].
```json
{
    "require": {
        "graze/event": "~0.1.0"
    }
}
```


### Usage ###
To subscribe to an event, you only need to implement the `getSubscribedEvents`
method from `Graze\Event\EventSubscriberInterface`. This routes an event to the
method of the same name in the subscriber.

```php
<?php

/**
 * `FooSubscriber::onFoo()` will be called when `event.foo` is dispatched.
 */
class FooSubscriber implements Graze\Event\EventSubscriberInterface
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

$dispatcher = new Graze\Event\EventDispatcher();
$dispatcher->addSubscriber(new FooSubscriber());

$dispatcher->dispatch('event.foo'); // Hello, World!
```

As well as string values, callable values are also supported. This allows you
to have conditional subscription and other much more complicated use cases.

```php
<?php

/**
 * `FooSubscriber::onFoo()` will be called when `event.foo` is dispatched with arguments.
 */
class FooSubscriber implements Graze\Event\EventSubscriberInterface
{
    public function getSubscribedEvents()
    {
        $self = $this;

        return array(
            'event.foo' => function() use($self) {
                if (0 < func_num_args()) {
                    $self->onFoo(func_get_args());
                }
            }
        );
    }

    public function onFoo($who)
    {
        echo "Hello, $who!";
    }
}

$dispatcher = new Graze\Event\EventDispatcher();
$dispatcher->addSubscriber(new FooSubscriber());

$dispatcher->dispatch('event.foo'); // null
$dispatcher->dispatch('event.foo', array('World')); // Hello, World!
```


### Contributing ###
We accept contributions to the source via Pull Request,
but passing unit tests must be included before it will be considered for merge.
```bash
$ make install
$ make tests
```

If you have [Vagrant][vagrant] installed, you can build our dev environment to assist development.
The Vagrant setup comes with multiple PHP environments to be able to work and test on relevant versions,
so you must specify which version you want to setup and SSH into.
The repository will be mounted in `/srv`.
```bash
$ vagrant up  [v53|v54]
$ vagrant ssh [v53|v54]

Welcome to Ubuntu 12.04 LTS (GNU/Linux 3.2.0-23-generic x86_64)
$ cd /srv
```


### License ###
The content of this library is released under the **MIT License** by **Nature Delivered Ltd**.<br/>
You can find a copy of this license at http://www.opensource.org/licenses/mit


<!-- Links -->
[travis]: https://travis-ci.org/graze/event
[travis-master]: https://travis-ci.org/graze/event.png?branch=master
[composer]: http://getcomposer.org
[vagrant]: http://vagrantup.com
