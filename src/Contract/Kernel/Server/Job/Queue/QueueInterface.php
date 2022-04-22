<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\OptionsInterface;
use Nyxio\Kernel\Server\Job\TaskData;

interface QueueInterface
{
    public function push(string $job, mixed $data, ?OptionsInterface $options = null): void;

    /**
     * @return TaskData[]
     */
    public function getQueue(): array;

    public function complete(string $uuid): void;
}
