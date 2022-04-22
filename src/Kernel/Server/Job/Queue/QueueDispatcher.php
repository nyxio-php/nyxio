<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\DispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueDispatcherInterface;
use Nyxio\Kernel\Server\Job\TaskData;
use Ramsey\Uuid\Uuid;

class QueueDispatcher implements QueueDispatcherInterface
{
    public function __construct(private readonly DispatcherInterface $dispatcher)
    {
    }

    public function launch(): void
    {
        $this->dispatcher->dispatch(
            new TaskData(job: QueueJob::class, uuid: Uuid::uuid4()->toString())
        );
    }
}
