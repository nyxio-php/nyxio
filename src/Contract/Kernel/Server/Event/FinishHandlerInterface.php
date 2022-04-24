<?php

namespace Nyxio\Contract\Kernel\Server\Event;

use Swoole\Http\Server;

interface FinishHandlerInterface
{
    public function handle(Server $server, int $taskId, mixed $returnData): void;
}
