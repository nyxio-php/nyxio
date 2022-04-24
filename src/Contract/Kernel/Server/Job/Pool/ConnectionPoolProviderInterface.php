<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Pool;

interface ConnectionPoolProviderInterface
{
    public function register(string $key, \Closure $closure): static;

    public function getAllRegisterClosures(): array;
}
