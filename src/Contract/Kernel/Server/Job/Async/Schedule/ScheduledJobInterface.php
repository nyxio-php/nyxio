<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Async\Schedule;

interface ScheduledJobInterface
{
    public function handle(): void;
}
