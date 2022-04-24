<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server;

use Nyxio\Kernel\Server\Job\Pool\ConnectionPool;
use Nyxio\Kernel\Server\Job\Pool\ConnectionPoolProvider;
use Nyxio\Kernel\Server\Starter;
use Nyxio\Tests\Kernel\Server\Job\Pool\Fixtures\Database;
use PHPUnit\Framework\TestCase;
use Swoole\Http\Server;

class StarterTest extends TestCase
{
    /**
     * @param int $workerId
     * @return void
     *
     * @dataProvider getDataProvider
     */
    public function testBasic(int $workerId): void
    {
        $server = \Mockery::mock(Server::class, [
            'start' => true,
        ]);

        $server->setting['worker_num'] = 4;
        $server->setting['task_worker_num'] = 4;

        $poolProvider = new ConnectionPoolProvider();

        $poolProvider->register('database', static function () {
            return new Database('mysql');
        });

        $poolProvider->register('redis', static function () {
            return new Database('redis');
        });

        $pool = new ConnectionPool(server: $server);

        $starter = new Starter(
            server:                 $server,
            connectionPoolProvider: $poolProvider,
            connectionPool:         $pool,
        );

        $starter->start();

        $server->allows(['getWorkerId' => $workerId]);
        $this->assertEquals('mysql', $pool->get('database')->connectionId);
        $this->assertEquals('redis', $pool->get('redis')->connectionId);
    }

    public function testInvalidProviderPoolCall(): void
    {
        $server = \Mockery::mock(Server::class, [
            'start' => true,
        ]);

        $server->setting['worker_num'] = 4;
        $server->setting['task_worker_num'] = 4;

        $poolProvider = new ConnectionPoolProvider();

        $poolProvider->register('database', static function () {
            throw new \Exception('test');
        });

        $pool = new ConnectionPool(server: $server);

        $starter = new Starter(
            server:                 $server,
            connectionPoolProvider: $poolProvider,
            connectionPool:         $pool,
        );

        $starter->start();

        $server->allows(['getWorkerId' => 5]);
        $this->assertNull($pool->get('database'));
    }

    private function getDataProvider(): \Generator
    {
        yield ['worker' => 4];
        yield ['worker' => 5];
        yield ['worker' => 6];
        yield ['worker' => 7];
    }
}
