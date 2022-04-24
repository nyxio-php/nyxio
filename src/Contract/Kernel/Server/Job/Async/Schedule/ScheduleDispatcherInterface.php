<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Async\Schedule;

interface ScheduleDispatcherInterface
{
    public function launch(array $jobs): void;
}
