<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Await;

interface TaskInterface
{
    public function run(string|\Closure $job, array $data = [], OptionsInterface $options = null): mixed;
}
