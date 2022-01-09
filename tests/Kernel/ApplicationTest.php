<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\KernelProvider;
use Nyxio\Kernel\Provider\WorkermanProvider;
use Nyxio\Kernel\ServerBridge;
use PHPUnit\Framework\TestCase;
use Psalm\Internal\EventDispatcher;

class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testBasic(): void
    {
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
                WorkermanProvider::class,
            ],
        ]);

        $application = new Application(config: $config);

        $application->bootstrap();

        $this->assertIsCallable($application->request());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration(): void
    {
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
            ],
        ]);

        $application = new Application(config: $config);

        $this->expectException(\ReflectionException::class);
        $application->bootstrap();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration2(): void
    {
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                WorkermanProvider::class,
            ],
        ]);

        $application = new Application(config: $config);

        $this->expectException(\ReflectionException::class);
        $application->bootstrap();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration3(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
                WorkermanProvider::class,
            ],
        ]);

        $application = new Application(config: $config, container: $container);
        $container->singleton(ProviderDispatcherInterface::class, EventDispatcher::class);

        $this->expectExceptionMessage(\sprintf('%s was not specified', ProviderDispatcherInterface::class));
        $this->expectException(\RuntimeException::class);
        $application->bootstrap();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration4(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
                WorkermanProvider::class,
            ],
        ]);

        $application = new Application(config: $config, container: $container);
        $container->singleton(ServerBridge::class, EventDispatcher::class);

        $this->expectExceptionMessage(\sprintf('%s was not specified', ServerBridge::class));
        $this->expectException(\RuntimeException::class);
        $application->bootstrap();
    }
}
