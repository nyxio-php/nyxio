<?php

declare(strict_types=1);

namespace Nyxio\Validation\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Validation
{
    public function __construct(public string $name)
    {
    }
}
