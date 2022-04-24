<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server\Job\Async;

interface OptionsInterface
{
    public function getDelay(): ?int;

    public function getRetryCount(): ?int;

    public function decreaseRetryCount(): static;

    public function getRetryDelay(): ?int;

    public function getFinishCallback(): ?\Closure;

    public function resetFinishCallback(): static;
}
