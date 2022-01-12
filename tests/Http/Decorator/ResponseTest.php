<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Decorator;

use Nyxio\Http\Response;
use Nyxio\Tests\Http\Decorator\Fixture\Dto;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testJson(): void
    {
        $response = new Response(new \Nyholm\Psr7\Response());

        $response = $response->json(['test' => true], 201);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"test":true}', (string)$response->getBody());
        $this->assertEquals(['application/json'], $response->getHeader('Content-Type'));
    }

    public function testException(): void
    {
        $response = new Response(new \Nyholm\Psr7\Response());

        $response = $response->json(new Dto(), 201);

        $this->assertEquals('', (string)$response->getBody());
    }

    public function testStatus(): void
    {
        $response = new Response(new \Nyholm\Psr7\Response());
        $response->status(404);
        $this->assertEquals(404, $response->json([])->getStatusCode());
    }

    public function testHeader(): void
    {
        $response = new Response(new \Nyholm\Psr7\Response());
        $response->header('test-header', 'test');
        $this->assertEquals(['test'], $response->json([])->getHeader('test-header'));
    }
}
