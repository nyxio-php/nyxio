<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request\Fixture;

use Nyxio\Contract\Http\Method;
use Nyxio\Http\Request;
use Nyxio\Http\Response;
use Nyxio\Routing\Attribute\Middleware;
use Nyxio\Routing\Attribute\Route;
use Nyxio\Routing\Attribute\RouteGroup;
use Psr\Http\Message\ResponseInterface;

#[Route(Method::POST, '/user/@id', rules: ['id' => 'integer'])]
#[RouteGroup('api')]
#[Middleware(TestMiddleware::class)]
class TestAction
{
    public function handle(Request $request, Response $response): ResponseInterface
    {
        return $response->json('test', 201);
    }
}
