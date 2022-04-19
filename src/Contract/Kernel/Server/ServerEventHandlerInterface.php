<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server;

interface ServerEventHandlerInterface
{
    public function attach(string $event, string $class): static;
}
