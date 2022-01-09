<?php

declare(strict_types=1);

namespace Nyxio\Validation\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class RuleGroup
{
    public function __construct(public readonly string $name)
    {
    }
}
