<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Config\ConfigInterface;
use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueDispatcherInterface;
use Nyxio\Kernel\Server\Job\TaskData;
use Ramsey\Uuid\Uuid;
use Swoole\Http\Server;

class QueueDispatcher implements QueueDispatcherInterface
{
    private const DEFAULT_DELAY = 1000; // ms

    public function __construct(
        private readonly Server $server,
        private readonly ConfigInterface $config,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function launch(): void
    {
        $this->server->tick($this->config->get('server.queue.delay', self::DEFAULT_DELAY), [$this, 'dispatch']);
    }

    private function dispatch(): void
    {
        $this->dispatcher->dispatch(
            new TaskData(job: QueueJob::class, uuid: Uuid::uuid4()->toString())
        );
    }
}
