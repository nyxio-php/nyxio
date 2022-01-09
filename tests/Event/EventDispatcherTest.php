<?php

declare(strict_types=1);

namespace Nyxio\Tests\Event;

use Nyxio\Event\Dispatcher;
use Nyxio\Tests\Event\Fixture\Listener;
use Nyxio\Tests\Event\Fixture\TestEvent;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testAddListener(): void
    {
        $dispatcher = new Dispatcher();
        $closure = static fn() => true;
        $dispatcher->addListener('test.event', $closure);

        $this->assertEquals([$closure], $dispatcher->getListeners('test.event'));
    }

    public function testDispatch(): void
    {
        $dispatcher = new Dispatcher();
        $listener1 = new Listener();
        $listener2 = new Listener();

        $dispatcher->addListener(TestEvent::NAME, fn($event) => $listener1->invoke());
        $dispatcher->addListener(TestEvent::NAME, [$listener2, 'invoke']);
        $dispatcher->dispatch(TestEvent::NAME, new TestEvent());

        $this->assertTrue($listener1->isInvoked());
        $this->assertTrue($listener2->isInvoked());
    }

    public function testStopDispatch(): void
    {
        $dispatcher = new Dispatcher();
        $listener1 = new Listener();
        $listener2 = new Listener();

        $dispatcher->addListener(TestEvent::NAME, static function (TestEvent $event) use ($listener1) {
            $listener1->invoke();
            $event->stopPropagation();
        });

        $dispatcher->addListener(TestEvent::NAME, [$listener2, 'invoke']);

        $dispatcher->dispatch(TestEvent::NAME, new TestEvent());

        $this->assertTrue($listener1->isInvoked());
        $this->assertFalse($listener2->isInvoked());
    }

    public function testStopByException(): void
    {
        $dispatcher = new Dispatcher();
        $listener = new Listener();

        $dispatcher->addListener(TestEvent::NAME, static function () {
            throw new \RuntimeException('EXCEPTION');
        });

        $dispatcher->addListener(TestEvent::NAME, [$listener, 'invoke']);

        $dispatcher->dispatch(TestEvent::NAME, new TestEvent());

        $this->assertFalse($listener->isInvoked());
    }

    public function testInvalidListener(): void
    {
        $dispatcher = new Dispatcher();

        $this->expectException(\InvalidArgumentException::class);
        $dispatcher->addListener('test.name', []);

    }
}
