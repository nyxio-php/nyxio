<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Pool;

use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface;
use Swoole\Http\Server;

class ConnectionPool implements ConnectionPoolInterface
{
    private array $connection = [];

    public function __construct(private readonly Server $server)
    {
    }

    public function add(int $workerId, string $key, mixed $data): static
    {
        $this->connection[$workerId][$key] = $data;

        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->connection[$this->server->getWorkerId()][$key] ?? null;
    }
}
