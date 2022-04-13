<?php

declare(strict_types=1);

namespace Nyxio\Contract\Server;

use Swoole\Server;

interface ServerHandlerInterface
{
    public function handle(Server $server): void;
}
