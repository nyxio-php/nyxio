<?php

namespace Nyxio\Contract\Kernel\Server\Event;

use Swoole\Http\Request;
use Swoole\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $httpRequest, Response $httpResponse): void;
}
