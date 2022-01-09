<?php

declare(strict_types=1);

namespace Nyxio\Tests\Provider;

use Nyxio\Container\Container;
use Nyxio\Provider\Dispatcher;
use Nyxio\Tests\Provider\Fixture\NotProvider;
use Nyxio\Tests\Provider\Fixture\TestProvider;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testDispatch(): void
    {
        $container = new Container();
        $dispatcher = new Dispatcher($container);

        $dispatcher->dispatch([TestProvider::class]);

        $provider = $container->get(TestProvider::class);

        $this->assertInstanceOf(TestProvider::class, $provider);
        $this->assertTrue($provider->invoked);

    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidProvider(): void
    {
        $container = new Container();
        $dispatcher = new Dispatcher($container);

        $dispatcher->dispatch([NotProvider::class]);

        $provider = $container->get(NotProvider::class);

        $this->assertInstanceOf(NotProvider::class, $provider);
        $this->assertFalse($provider->invoked);
    }
}
