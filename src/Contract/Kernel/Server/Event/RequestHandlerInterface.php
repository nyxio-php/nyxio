<?php

namespace Nyxio\Contract\Kernel\Server\Event;

use Swoole\Http\Response;

interface RequestHandlerInterface
{
    /**
     * @param \Swoole\Http\Request $httpRequest
     * @param Response $httpResponse
     * @return void
     * @throws \JsonException
     */
    public function handle(\Swoole\Http\Request $httpRequest, Response $httpResponse): void;
}
