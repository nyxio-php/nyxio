<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Pool;

interface ConnectionPoolInterface
{
    public function add(int $workerId, string $key, mixed $data): static;

    public function get(string $key): mixed;
}
