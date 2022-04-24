<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Fixtures;

class TestJob
{

    public function handle(string $message): string
    {
        return $message;
    }
}
