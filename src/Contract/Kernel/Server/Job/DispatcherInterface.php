<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job;

use Nyxio\Kernel\Server\Job\TaskData;

interface DispatcherInterface
{
    public function dispatch(TaskData $taskData): void;
}
