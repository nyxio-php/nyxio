<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Text;

interface MessageInterface
{
    public function text(string $message, array $params = []): string;
}
