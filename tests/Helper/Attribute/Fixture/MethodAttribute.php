<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute\Fixture;

#[\Attribute(\Attribute::TARGET_METHOD)]
class MethodAttribute
{
    public function __construct(public readonly string $name)
    {
    }
}
