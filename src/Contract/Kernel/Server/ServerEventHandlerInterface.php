<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server;

use Swoole\Server;

interface ServerEventHandlerInterface
{
    public function handle(Server $server): void;
}
