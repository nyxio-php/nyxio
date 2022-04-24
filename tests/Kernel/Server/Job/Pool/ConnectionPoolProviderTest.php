<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Pool;

use Nyxio\Kernel\Server\Job\Pool\ConnectionPoolProvider;
use Nyxio\Tests\Kernel\Server\Job\Pool\Fixtures\Database;
use PHPUnit\Framework\TestCase;

class ConnectionPoolProviderTest extends TestCase
{
    public function testBasic(): void
    {
        $provider = new ConnectionPoolProvider();

        $provider->register('database', static function () {
            return new Database('mysql');
        });

        $provider->register('redis', static function () {
            return new Database('redis');
        });

        $databases = [];

        foreach ($provider->getAllRegisterClosures() as $key => $closure) {
            $databases[$key] = $closure();
        }

        $this->assertEquals('mysql', $databases['database']->connectionId);
        $this->assertEquals('redis', $databases['redis']->connectionId);
    }
}
