<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Utility;

interface UuidFactoryInterface
{
    public function generate(): string;
}
