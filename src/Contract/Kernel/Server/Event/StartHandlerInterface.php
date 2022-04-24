<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Event;

interface StartHandlerInterface
{
    public function handle(): void;
}
