<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request\Fixture;

use Nyxio\Contract\Http\MiddlewareInterface;
use Nyxio\Http\Request;
use Nyxio\Http\Response;
use Psr\Http\Message\ResponseInterface;

class TestMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, Response $response, \Closure $next): ResponseInterface
    {
        return $next($request, $response);
    }
}
