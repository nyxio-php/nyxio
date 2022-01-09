<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request\Fixture;

use Nyxio\Contract\Http\Method;
use Nyxio\Http\Request;
use Nyxio\Http\Response;
use Nyxio\Routing\Attribute\Route;
use Nyxio\Routing\Attribute\RouteGroup;
use Psr\Http\Message\ResponseInterface;

#[Route(Method::POST, 'test')]
#[RouteGroup('invalid-group')]
class ActionWithInvalidGroup
{
    public function handle(Request $request, Response $response): ResponseInterface
    {
        return $response->json('test', 201);
    }
}
