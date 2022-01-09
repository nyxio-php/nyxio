<?php

declare(strict_types=1);

namespace Nyxio\Tests\Routing;

use Nyxio\Contract\Http\Method;
use Nyxio\Routing\Attribute\Middleware;
use Nyxio\Routing\Attribute\Route;
use Nyxio\Routing\Attribute\RouteGroup;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{
    public function testRoute(): void
    {
        $route = new Route(method: Method::GET, uri: '/test-url');
        $route->addPrefix('/prefix/');
        $this->assertEquals('GET', $route->method->value);
        $this->assertEquals('/prefix/test-url', $route->getUri());
    }

    public function testRouteGroup(): void
    {
        $group = new RouteGroup('test');
        $this->assertEquals('test', $group->name);
    }

    public function testMiddleware(): void
    {
        $middleware = new Middleware('test');
        $this->assertEquals('test', $middleware->name);
    }
}
