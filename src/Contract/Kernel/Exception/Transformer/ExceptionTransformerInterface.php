<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Exception\Transformer;

interface ExceptionTransformerInterface
{
    public function toArray(\Throwable $exception): array|string;

    public function setDebug(bool $state): static;
}
