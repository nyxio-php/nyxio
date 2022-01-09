<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container;

use Nyxio\Container\Container;
use Nyxio\Tests\Container\Fixture\Bar;
use Nyxio\Tests\Container\Fixture\Foo;
use Nyxio\Tests\Container\Fixture\FooInterface;
use Nyxio\Tests\Container\Fixture\Logger\Factory\BadLoggerFactory;
use Nyxio\Tests\Container\Fixture\Logger\Factory\GoodLoggerFactory;
use Nyxio\Tests\Container\Fixture\Logger\Factory\LoggerFactoryInterface;
use Nyxio\Tests\Container\Fixture\Logger\Factory\SomeBadLoggerFactory;
use Nyxio\Tests\Container\Fixture\Logger\Factory\SomeGoodLoggerFactory;
use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;
use Nyxio\Tests\Container\Fixture\Logger\NotLogger;
use Nyxio\Tests\Container\Fixture\Logger\NullLogger;
use Nyxio\Tests\Container\Fixture\Logger\SupperLogger;
use Nyxio\Tests\Container\Fixture\Logger\TextLogger;
use Nyxio\Tests\Container\Fixture\ServiceWithLoggerFactory;
use PHPUnit\Framework\TestCase;

class SingletonTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testWithoutConstructor(): void
    {
        $container = new Container();
        $container->singleton(Foo::class);

        $this->assertInstanceOf(Foo::class, $container->get(Foo::class));
        $this->assertInstanceOf(FooInterface::class, $container->get(Foo::class));
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testSingletonAlias(): void
    {
        $container = new Container();
        $container->singleton(FooInterface::class, Foo::class);

        $this->assertInstanceOf(Foo::class, $container->get(FooInterface::class));
        $this->assertInstanceOf(FooInterface::class, $container->get(FooInterface::class));
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testExceptionUnionTypesWithoutDefaultValue(): void
    {
        $container = new Container();
        $container->singleton(BadLoggerFactory::class);

        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Argument $logger in %s:__construct can\'t be set: union parameter without default value: %s|string',
                BadLoggerFactory::class,
                LoggerInterface::class
            )
        );

        $container->get(BadLoggerFactory::class);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testExceptionConstructorWithoutDefaultBuiltin(): void
    {
        $container = new Container();
        $container->singleton(LoggerInterface::class, NullLogger::class);
        $container->singleton(SomeBadLoggerFactory::class);

        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Property $%s has no default value (%s::__construct)',
                'channel',
                SomeBadLoggerFactory::class,
            )
        );

        $container->get(SomeBadLoggerFactory::class);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testDefaultInstance(): void
    {
        $container = new Container();
        $container->singleton(LoggerInterface::class, SupperLogger::class);
        $container->singleton(GoodLoggerFactory::class);

        /** @var GoodLoggerFactory $instance */
        $instance = $container->get(GoodLoggerFactory::class);

        $this->assertInstanceOf(NullLogger::class, $instance->logger);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidType(): void
    {
        $container = new Container();
        $container->singleton(LoggerInterface::class, NotLogger::class);
        $container->singleton(SomeGoodLoggerFactory::class);

        $this->expectException(\ReflectionException::class);
        $container->get(SomeGoodLoggerFactory::class);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testRegisterByClosure(): void
    {
        $container = new Container();

        $container->singletonFn(
            LoggerFactoryInterface::class,
            fn() => new GoodLoggerFactory(new TextLogger(), 'console')
        );

        /** @var ServiceWithLoggerFactory $instance */
        $instance = $container->get(ServiceWithLoggerFactory::class);

        /** @var GoodLoggerFactory $loggerFactory */
        $loggerFactory = $instance->loggerFactory;

        $this->assertInstanceOf(GoodLoggerFactory::class, $loggerFactory);
        $this->assertEquals('console', $loggerFactory->channel);
        $this->assertInstanceOf(TextLogger::class, $loggerFactory->logger);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testGetWithAdditionalParams(): void
    {
        $container = new Container();

        $instance = $container->get(Bar::class, [
            'property' => 'bar',
        ]);

        $this->assertInstanceOf(Bar::class, $instance);
        $this->assertInstanceOf(Foo::class, $instance->foo);
        $this->assertEquals('bar', $instance->property);
    }
}
