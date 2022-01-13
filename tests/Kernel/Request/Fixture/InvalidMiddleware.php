<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request\Fixture;

use Nyxio\Http\Request;
use Nyxio\Http\Response;
use Psr\Http\Message\ResponseInterface;

class InvalidMiddleware
{
    public function handle(Request $request, Response $response, \Closure $next): ResponseInterface
    {
        return $response->json('invalid middleware');
    }
}
