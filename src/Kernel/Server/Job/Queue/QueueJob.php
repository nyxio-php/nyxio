<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;
use Swoole\Http\Server;

class QueueJob
{
    public function __construct(
        private readonly Server $server,
        private readonly QueueInterface $queue,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function handle(): void
    {
        $this->server->after(1000, function () {
            foreach ($this->queue->getQueue() as $taskData) {
                $this->dispatcher->dispatch($taskData);

                $this->queue->complete($taskData->uuid);
            }
        });
    }
}
