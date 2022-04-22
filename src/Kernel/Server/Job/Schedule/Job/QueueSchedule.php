<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Schedule\Job;

use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;
use Nyxio\Contract\Kernel\Server\Job\Schedule\ScheduledJobInterface;
use Nyxio\Kernel\Server\Job\Schedule\Attribute\Schedule;

#[Schedule(expression: '*/1 * * * *')]
class QueueSchedule implements ScheduledJobInterface
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
        }
    }
}
