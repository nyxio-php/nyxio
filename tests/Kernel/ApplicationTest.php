<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Event\Dispatcher;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\KernelProvider;
use Nyxio\Kernel\Provider\ServerProvider;
use Nyxio\Kernel\Server\Starter;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Server;

class ApplicationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     * @runInSeparateProcess
     */
    public function testInvalidConfiguration1(): void
    {
        $container = new Container();
        $config = (new MemoryConfig())
            ->addConfig('app', [
                'providers' => [
                    KernelProvider::class,
                ],
            ])
            ->addConfig('server', ['port' => 1245]);

        $application = new Application(config: $config, container: $container);
        $container->singleton(ProviderDispatcherInterface::class, Dispatcher::class);

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
        $config = (new MemoryConfig())
            ->addConfig('app', [
                'providers' => [
                    KernelProvider::class,
                    ServerProvider::class,
                ],
            ])
            ->addConfig('server', ['port' => 1245]);

        $application = new Application(config: $config, container: $container);
        $application->bootstrap();
        $container->singleton(Starter::class, Dispatcher::class);

        $this->expectExceptionMessage(\sprintf('%s was not specified', Starter::class));
        $this->expectException(\RuntimeException::class);
        $application->start();
    }

    /**
     * @return void
     * @throws \ReflectionException
     * @runInSeparateProcess
     */
    public function testApplicationStart(): void
    {
        $container = new Container();

        $config = (new MemoryConfig())
            ->addConfig('app', [
                'providers' => [
                    KernelProvider::class,
                    ServerProvider::class,
                ],
            ]);

        $application = new Application(config: $config, container: $container);

        $application->bootstrap();

        $container->singletonFn(Server::class, static function () {
            $server = \Mockery::mock(Server::class, [
                'start' => true,
                'on' => true,
            ]);

            $server->setting['worker_num'] = 4;
            $server->setting['task_worker_num'] = 4;

            return $server;
        });

        $this->assertTrue($application->start());
    }
}
