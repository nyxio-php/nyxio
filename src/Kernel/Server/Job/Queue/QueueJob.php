<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;

class QueueJob
{
    public function __construct(
        private readonly QueueInterface $queue,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function handle(): void
    {
        foreach ($this->queue->getQueue() as $taskData) {
            $this->dispatcher->dispatch($taskData);

            $this->queue->complete($taskData->uuid);
        }
    }
}
