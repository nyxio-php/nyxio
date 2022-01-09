<?php

declare(strict_types=1);

namespace Nyxio\Contract\Http;

use Nyxio\Http\Request;
use Nyxio\Http\Response;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response, \Closure $next): ResponseInterface;
}
