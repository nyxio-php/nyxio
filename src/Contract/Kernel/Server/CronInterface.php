<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server;

interface CronInterface
{
    public function handle(): void;
}
