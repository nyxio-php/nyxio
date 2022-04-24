<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server;

use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface;
use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolProviderInterface;
use Swoole\Constant;
use Swoole\Http\Server;

class Starter
{
    public function __construct(
        private readonly Server $server,
        private readonly ConnectionPoolProviderInterface $connectionPoolProvider,
        private readonly ConnectionPoolInterface $connectionPool
    ) {
    }

    public function start(): void
    {
        $this->connectionPoolInit();

        $this->server->start();
    }

    private function connectionPoolInit(): void
    {
        $tasksCount = $this->server->setting[Constant::OPTION_TASK_WORKER_NUM];
        $workersCount = $this->server->setting[Constant::OPTION_WORKER_NUM];

        foreach ($this->connectionPoolProvider->getAllRegisterClosures() as $key => $closure) {
            try {
                for ($workerId = $tasksCount - 1; $workerId >= 0; $workerId--) {
                    $this->connectionPool->add(
                        $workersCount + $workerId,
                        $key,
                        $closure(),
                    );
                }
            } catch (\Throwable $exception) {
                echo
                    \sprintf(
                        'Connection Pool Provider (%s) create instance error: %s',
                        $key,
                        $exception->getMessage()
                    )
                    . \PHP_EOL;
            }
        }
    }
}
