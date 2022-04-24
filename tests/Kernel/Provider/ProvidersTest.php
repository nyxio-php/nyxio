<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Provider;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Event\EventDispatcherInterface;
use Nyxio\Contract\Kernel\Exception\Transformer\ExceptionTransformerInterface;
use Nyxio\Contract\Kernel\Request\ActionCollectionInterface;
use Nyxio\Contract\Kernel\Request\RequestHandlerInterface;
use Nyxio\Contract\Kernel\Server\Event\FinishHandlerInterface;
use Nyxio\Contract\Kernel\Server\Event\StartHandlerInterface;
use Nyxio\Contract\Kernel\Server\Event\TaskHandlerInterface;
use Nyxio\Contract\Kernel\Server\Event\WorkerStartHandlerInterface;
use Nyxio\Contract\Kernel\Server\Job\Async;
use Nyxio\Contract\Kernel\Server\Job\Async\Queue\QueueInterface;
use Nyxio\Contract\Kernel\Server\Job\Async\Schedule\ScheduleDispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Await;
use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface;
use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolProviderInterface;
use Nyxio\Contract\Kernel\Text\MessageInterface;
use Nyxio\Contract\Kernel\Utility\UuidFactoryInterface;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Contract\Routing\GroupCollectionInterface;
use Nyxio\Contract\Routing\UriMatcherInterface;
use Nyxio\Contract\Validation\RuleExecutorCollectionInterface;
use Nyxio\Contract\Validation\RulesCheckerInterface;
use Nyxio\Contract\Validation\ValidationInterface;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\KernelProvider;
use Nyxio\Kernel\Provider\ServerProvider;
use Nyxio\Kernel\Server\Starter;
use Nyxio\Routing\Group;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Swoole\Http\Server;

class ProvidersTest extends TestCase
{
    /**
     * @param string $alias
     * @param bool $singleton
     * @return void
     *
     * @dataProvider getDataProviderForKernel
     * @runInSeparateProcess
     */
    public function testKernelProvider(string $alias, bool $singleton): void
    {
        $container = new Container();
        $provider = new KernelProvider(
            $container, (new MemoryConfig())->addConfig('http', ['groups' => [new Group('test')]])
        );

        $provider->process();

        $this->assertTrue($singleton ? $container->hasSingleton($alias) : $container->hasBind($alias));
    }

    /**
     * @param string $alias
     * @param bool $singleton
     * @return void
     *
     * @dataProvider getDataProviderForServer
     * @runInSeparateProcess
     */
    public function testServerProvider(string $alias, bool $singleton): void
    {
        $container = new Container();

        $kernelProvider = new KernelProvider(
            $container, (new MemoryConfig())->addConfig('http', ['groups' => [new Group('test')]])
        );

        $kernelProvider->process();

        $provider = new ServerProvider(
            container: $container,
            config:    new MemoryConfig(),
        );

        $provider->process();

        $this->assertTrue($singleton ? $container->hasSingleton($alias) : $container->hasBind($alias));
    }

    private function getDataProviderForServer(): array
    {
        return [
            [Server::class, true],
            [Starter::class, true],

            [DispatcherInterface::class, true],

            [ScheduleDispatcherInterface::class, true],
            [QueueInterface::class, true],
            [Await\AwaitTaskInterface::class, true],

            [Async\TaskHandlerInterface::class, true],
            [Await\TaskHandlerInterface::class, true],
            [ConnectionPoolProviderInterface::class, true],
            [ConnectionPoolInterface::class, true],
            [StartHandlerInterface::class, true],
            [\Nyxio\Contract\Kernel\Server\Event\RequestHandlerInterface::class, true],
            [FinishHandlerInterface::class, true],
            [TaskHandlerInterface::class, true],
            [WorkerStartHandlerInterface::class, true],
        ];
    }

    private function getDataProviderForKernel(): array
    {
        return [
            [Application::class, true],
            [ActionCollectionInterface::class, true],
            [ExceptionTransformerInterface::class, true],
            [RequestHandlerInterface::class, true],
            [ProviderDispatcherInterface::class, true],
            [EventDispatcherInterface::class, true],

            [UriFactoryInterface::class, true],
            [StreamFactoryInterface::class, true],
            [UploadedFileFactoryInterface::class, true],
            [ServerRequestFactoryInterface::class, true],
            [ResponseFactoryInterface::class, true],

            [GroupCollectionInterface::class, true],
            [UriMatcherInterface::class, false],

            [RuleExecutorCollectionInterface::class, true],
            [RulesCheckerInterface::class, true],
            [ValidationInterface::class, false],

            [MessageInterface::class, true],
            [UuidFactoryInterface::class, true],
        ];
    }
}
