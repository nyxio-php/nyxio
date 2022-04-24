<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Event;

use Nyxio\Kernel\Server\Event\FinishHandler;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Server;

class FinishEventHandlerTest extends TestCase
{
    public function testBasic(): void
    {
        $handler = new FinishHandler();
        $handler->handle(
            server:     \Mockery::mock(Server::class),
            taskId:     1,
            returnData: 'test'
        );

        $this->assertTrue(true);
    }
}
