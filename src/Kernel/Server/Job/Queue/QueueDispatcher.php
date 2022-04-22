<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Config\ConfigInterface;
use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueDispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;
use Swoole\Http\Server;

class QueueDispatcher implements QueueDispatcherInterface
{
    private const DEFAULT_DELAY = 1000; // ms

    public function __construct(
        private readonly Server $server,
        private readonly ConfigInterface $config,
        private readonly QueueInterface $queue,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function launch(): void
    {
        $this->server->tick($this->config->get('app.queue.delay', self::DEFAULT_DELAY), function (): void {
            foreach ($this->queue->getQueue() as $taskData) {
                $this->dispatcher->dispatch($taskData);
            }
        });
    }
}
