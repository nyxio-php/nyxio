<?php

declare(strict_types=1);

namespace Nyxio\Contract\Queue;

interface QueueInterface
{
    public function push(
        string $job,
        array $data = [],
        \Closure $finishCallback = null,
        ?OptionsInterface $options = null
    ): void;
}
