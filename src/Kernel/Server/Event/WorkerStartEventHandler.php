<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Event;

use Nyxio\Contract\Config\ConfigInterface;
use Nyxio\Contract\Kernel\Server\Job\Async\Schedule\ScheduleDispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface;
use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolProviderInterface;
use Swoole\Constant;
use Swoole\Http\Server;

/**
 * @codeCoverageIgnore
 */
class WorkerStartEventHandler
{
    public function __construct(
        private readonly ScheduleDispatcherInterface $scheduleDispatcher,
        private readonly ConfigInterface $config,
        private readonly ConnectionPoolProviderInterface $connectionPoolProvider,
        private readonly ConnectionPoolInterface $connectionPool,
    ) {
    }

    public function handle(Server $server, int $workerId): void
    {
        if ($workerId === 0) {
            $this->scheduleDispatcher->launch($this->config->get('schedule.jobs', []));
        }

        if ($workerId <= $server->setting[Constant::OPTION_WORKER_NUM]) {
            foreach ($this->connectionPoolProvider->getAllRegisterClosures() as $key => $closure) {
                try {
                    $this->connectionPool->add(
                        $workerId,
                        $key,
                        $closure(),
                    );
                } catch (\Throwable $exception) {
                    echo \sprintf(
                            'Connection Pool Provider (%s) create instance error: %s',
                            $key,
                            $exception->getMessage()
                        )
                        . \PHP_EOL;
                }
            }
        }
    }
}
