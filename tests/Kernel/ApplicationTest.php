<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Kernel\Server\ServerEventHandlerInterface;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\KernelProvider;
use Nyxio\Kernel\Provider\ServerProvider;
use PHPUnit\Framework\TestCase;
use Psalm\Internal\EventDispatcher;

class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration1(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
                ServerProvider::class,
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
     * @runInSeparateProcess
     */
    public function testInvalidConfiguration2(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
            ],
        ]);

        $application = new Application(config: $config, container: $container);

        $this->expectExceptionMessage(\sprintf('%s was not specified', ServerEventHandlerInterface::class));
        $this->expectException(\RuntimeException::class);
        $application->bootstrap();

        $application->bootstrap();
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @runInSeparateProcess
     */
    public function testApplicationBootstrap(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                KernelProvider::class,
                ServerProvider::class,
            ],
        ]);

        $application = new Application(config: $config, container: $container);

        $this->assertInstanceOf(Application::class, $application->bootstrap());
    }
}
