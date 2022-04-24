<?php

namespace Nyxio\Contract\Kernel\Server\Event;

use Swoole\Http\Server;

interface WorkerStartHandlerInterface
{
    public function handle(Server $server, int $workerId): void;
}
