<?php

declare(strict_types=1);

namespace Nyxio\Validation\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Rule
{
    public function __construct(public readonly string $short, public readonly string $message)
    {
    }
}
