<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Factory;

use Nyxio\Http\Factory\ResponseFactory;
use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    public function testBasic(): void
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', $response->getReasonPhrase());
    }

    public function testWithParams(): void
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse(201, 'test');
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('test', $response->getReasonPhrase());
    }
}
