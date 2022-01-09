<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute\Fixture;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ClassAttribute
{
    public function __construct(public readonly string $name)
    {
    }
}
