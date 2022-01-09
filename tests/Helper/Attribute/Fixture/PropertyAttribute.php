<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute\Fixture;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PropertyAttribute
{
    public function __construct(public readonly string $name)
    {
    }
}
