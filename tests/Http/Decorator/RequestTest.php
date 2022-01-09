<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Decorator;

use Nyxio\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testBasic(): void
    {
        $serverRequest = new \Nyholm\Psr7\ServerRequest('GET', '/', serverParams: ['REQUEST_TEST' => 'test']);
        $serverRequest = $serverRequest
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Token', 'test')
            ->withCookieParams(['Authorization' => 'Bearer 12346'])
            ->withQueryParams(['a' => true, 'b' => 2])
            ->withParsedBody(['name' => 'Test']);

        $request = new Request($serverRequest);

        // Without params
        $this->assertEquals(
            [
                'Content-Type' => 'application/json',
                'Token' => 'test',
            ],
            $request->header()
        );

        $this->assertEquals(['a' => true, 'b' => 2], $request->get());
        $this->assertEquals(['name' => 'Test'], $request->post());
        $this->assertEquals(['REQUEST_TEST' => 'test'], $request->server());
        $this->assertEquals(['Authorization' => 'Bearer 12346'], $request->cookie());

        // With params
        $this->assertEquals('application/json', $request->header('Content-Type'));
        $this->assertEquals('application/json', $request->header('content-type'));
        $this->assertEquals(false, $request->header('unknown-header', false));

        $this->assertEquals(true, $request->get('a'));
        $this->assertEquals(2, $request->get('b'));
        $this->assertEquals(false, $request->get('c', false));

        $this->assertEquals('Test', $request->post('name', false));
        $this->assertEquals(false, $request->post('test', false));

        $this->assertEquals('test', $request->server('REQUEST_TEST', false));
        $this->assertEquals(false, $request->post('REQUEST_TEST_INVALID', false));

        $this->assertEquals('Bearer 12346', $request->cookie('Authorization', false));
        $this->assertEquals(false, $request->post('Authorization Invalid Cookie', false));
    }

    public function testBodyNotArray(): void
    {
        $serverRequest = new \Nyholm\Psr7\ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withParsedBody(null);
        $request = new Request($serverRequest);

        $this->assertEquals(1337, $request->post(default: 1337));
    }
}
