<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container;

use Nyxio\Container\Container;
use Nyxio\Contract\Container\ContainerInterface;
use Nyxio\Tests\Container\Fixture\Bar;
use Nyxio\Tests\Container\Fixture\Foo;
use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;
use Nyxio\Tests\Container\Fixture\Logger\TextLogger;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testWithoutRegister(): void
    {
        $container = new Container();

        $instance = $container->get(Bar::class);

        $this->assertInstanceOf(Bar::class, $instance);
        $this->assertInstanceOf(Foo::class, $instance->foo);
        $this->assertEquals('test', $instance->property);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testRegisterWithArguments(): void
    {
        $container = new Container();

        $container->singleton(TextLogger::class)->addArgument('message', 'channel');
        $container->singleton(LoggerInterface::class, TextLogger::class)->addArgument('message', 'swim');

        $instance = $container->get(TextLogger::class);
        $this->assertInstanceOf(TextLogger::class, $instance);
        $this->assertEquals('channel', $instance->message);

        $instance = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(TextLogger::class, $instance);
        $this->assertEquals('swim', $instance->message);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testRegisterBindWithArguments(): void
    {
        $container = new Container();

        $container->bind(LoggerInterface::class, TextLogger::class)->addArgument('message', 'test-bind');

        $instance = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(TextLogger::class, $instance);
        $this->assertEquals('test-bind', $instance->message);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testGetSelf(): void
    {
        $container = new Container();

        $this->assertInstanceOf(ContainerInterface::class, $container->get(ContainerInterface::class));
    }

    public function testInvalidInstance(): void
    {
        $container = new Container();

        $this->expectException(\ReflectionException::class);
        $container->get(LoggerInterface::class);
    }

    public function testGetInjectionArgument(): void
    {
        $container = new Container();

        $injection = $container->singleton('foo')->addArgument('test', 1);
        $this->assertTrue($injection->hasArgument('test'));
        $this->assertEquals(1, $injection->getArgument('test'));
    }
}
