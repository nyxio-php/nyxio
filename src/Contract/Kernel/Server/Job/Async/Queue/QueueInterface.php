<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Async\Queue;

use Nyxio\Contract\Kernel\Server\Job\Async\OptionsInterface;

interface QueueInterface
{
    public function push(string $job, array $data = [], ?OptionsInterface $options = null): void;
}
