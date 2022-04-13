<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\HttpServerProvider;
use Nyxio\Kernel\Provider\KernelProvider;
use PHPUnit\Framework\TestCase;
use Psalm\Internal\EventDispatcher;

class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidConfiguration2(): void
    {
        $config = (new MemoryConfig())->addConfig('app', [
            'providers' => [
                HttpServerProvider::class,
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
                HttpServerProvider::class,
            ],
        ]);

        $application = new Application(config: $config, container: $container);
        $container->singleton(ProviderDispatcherInterface::class, EventDispatcher::class);

        $this->expectExceptionMessage(\sprintf('%s was not specified', ProviderDispatcherInterface::class));
        $this->expectException(\RuntimeException::class);
        $application->bootstrap();
    }
}
