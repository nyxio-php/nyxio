<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Pool\Fixtures;

class Database
{
    public function __construct(public readonly string $connectionId)
    {
    }
}
