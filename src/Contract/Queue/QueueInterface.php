<?php

declare(strict_types=1);

namespace Nyxio\Contract\Queue;

interface QueueInterface
{
    public function push(
        string $job,
        array $data = [],
        ?OptionsInterface $options = null,
        \Closure $finishCallback = null,
    ): void;
}
