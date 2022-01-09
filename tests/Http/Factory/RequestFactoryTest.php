<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Factory;

use Nyxio\Http\Factory\RequestFactory;
use Nyxio\Http\Factory\UriFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    public function testBasic(): void
    {
        $factory = new RequestFactory(new UriFactory());
        $request = $factory->createServerRequest('GET', '/test/', ['HOST' => 'test']);
        $this->assertEquals(['HOST' => 'test'], $request->getServerParams());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/test', (string)$request->getUri());
    }

    public function testQueryParams(): void
    {
        $factory = new RequestFactory(new UriFactory());
        $request = $factory->createServerRequest('GET', '/test?foo=true');
        $this->assertEquals(['foo' => true], $request->getQueryParams());
    }
}
