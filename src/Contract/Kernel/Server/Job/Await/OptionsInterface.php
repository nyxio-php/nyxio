<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Await;

interface OptionsInterface
{
    public function getTimeout(): float;
}
