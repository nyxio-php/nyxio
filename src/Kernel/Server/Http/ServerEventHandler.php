<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Http;

use Nyxio\Contract\Kernel\Server\ServerEventHandlerInterface;
use Nyxio\Kernel\Server\Http\Event\RequestEventHandler;
use Swoole\Http\Server;

class ServerEventHandler implements ServerEventHandlerInterface
{
    public function __construct(
        private readonly Server $server,
        private readonly RequestEventHandler $requestEventHandler
    ) {
    }

    public function handle(): void
    {
        $this->server->on('request', [$this->requestEventHandler, 'handle']);
    }
}
