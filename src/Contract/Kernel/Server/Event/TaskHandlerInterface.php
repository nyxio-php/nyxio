<?php

namespace Nyxio\Contract\Kernel\Server\Event;

use Nyxio\Kernel\Server\Job\TaskData;
use Swoole\Http\Server;

interface TaskHandlerInterface
{
    public function handle(Server $server, int $taskId, int $reactorId, TaskData $taskData): mixed;
}
