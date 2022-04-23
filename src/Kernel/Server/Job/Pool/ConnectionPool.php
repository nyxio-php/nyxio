<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Pool;

use Nyxio\Contract\Kernel\Server\Job\Pool\ConnectionPoolInterface;

class ConnectionPool implements ConnectionPoolInterface
{
    private array $connection = [];

    public function add(int $workerId, string $key, mixed $data): static
    {
        $this->connection[$workerId][$key] = $data;

        return $this;
    }

    public function get(int $workerId, string $key): mixed
    {
        return $this->connection[$workerId][$key] ?? null;
    }
}
