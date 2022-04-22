<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueDispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;

class QueueDispatcher implements QueueDispatcherInterface
{
    public function __construct(
        private readonly QueueInterface $queue,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function launch(): void
    {
        foreach ($this->queue->getQueue() as $taskData) {
            $this->dispatcher->dispatch($taskData);
        }
    }
}
