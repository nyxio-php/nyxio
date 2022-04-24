<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Provider;

use Nyxio\Contract;
use Nyxio\Contract\Config\ConfigInterface;
use Nyxio\Contract\Container\ContainerInterface;
use Nyxio\Contract\Provider\ProviderInterface;
use Nyxio\Kernel;
use Swoole\Http\Server;

class ServerProvider implements ProviderInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ConfigInterface $config
    ) {
    }

    public function process(): void
    {
        $this->container->singletonFn(Server::class, function () {
            $server = new Server(
                $this->config->get('server.host', '127.0.0.1'),
                $this->config->get('server.port', 9501)
            );

            $server->set($this->config->get('server.options', []));

            return $server;
        });

        $this->container->singleton(
            Contract\Kernel\Server\Job\DispatcherInterface::class,
            Kernel\Server\Job\Dispatcher::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Await\AwaitTaskInterface::class,
            Kernel\Server\Job\Await\AwaitTask::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Async\Queue\QueueInterface::class,
            Kernel\Server\Job\Async\Queue\Queue::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Async\Schedule\ScheduleDispatcherInterface::class,
            Kernel\Server\Job\Async\Schedule\ScheduleDispatcher::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Await\TaskHandlerInterface::class,
            Kernel\Server\Job\Await\TaskHandler::class,
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Async\TaskHandlerInterface::class,
            Kernel\Server\Job\Async\TaskHandler::class,
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Pool\ConnectionPoolProviderInterface::class,
            Kernel\Server\Job\Pool\ConnectionPoolProvider::class,
        );

        $this->container->singleton(
            Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface::class,
            Kernel\Server\Job\Pool\ConnectionPool::class,
        );

        $this->container->singleton(Kernel\Server\Starter::class);

        $server = $this->container->get(Server::class);

        if ($server instanceof Server) {
            $this->events($server);
        }
    }

    /**
     * @param Server $server
     * @return void
     *
     * @codeCoverageIgnore
     */
    private function events(Server $server): void
    {
        $this->container->singleton(
            Contract\Kernel\Server\Event\StartHandlerInterface::class,
            Kernel\Server\Event\StartHandler::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Event\RequestHandlerInterface::class,
            Kernel\Server\Event\RequestHandler::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Event\TaskHandlerInterface::class,
            Kernel\Server\Event\TaskHandler::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Event\FinishHandlerInterface::class,
            Kernel\Server\Event\FinishHandler::class
        );

        $this->container->singleton(
            Contract\Kernel\Server\Event\WorkerStartHandlerInterface::class,
            Kernel\Server\Event\WorkerStartHandler::class
        );
    }
}
