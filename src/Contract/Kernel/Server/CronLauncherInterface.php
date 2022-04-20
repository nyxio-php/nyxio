<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server;

interface CronLauncherInterface
{
    public function launch(array $jobs): void;
}
