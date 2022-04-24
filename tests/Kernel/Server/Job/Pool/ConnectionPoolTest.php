<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Pool;

use Mockery;
use Nyxio\Kernel\Server\Job\Pool\ConnectionPool;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Server;

class ConnectionPoolTest extends TestCase
{
    public function testBasic(): void
    {
        $pool = new ConnectionPool($this->getServerMock(workerId: 5, workerNum: 4));
        $pool->add(5, 'database', 'test');

        $this->assertEquals('test', $pool->get('database'));
    }

    public function testUseOutsideAwaitAsyncTasks(): void
    {
        $pool = new ConnectionPool($this->getServerMock(workerId: 2, workerNum: 6));
        $pool->add(2, 'database', 'test');

        $this->expectException(\RuntimeException::class);
        $pool->get('database');
    }

    private function getServerMock(int $workerId, int $workerNum): array|Mockery\MockInterface|Server|Mockery\LegacyMockInterface
    {
        $server = Mockery::mock(Server::class, [
            'getWorkerId' => $workerId,
        ]);

        $server->setting['worker_num'] = $workerNum;

        return $server;
    }
}
