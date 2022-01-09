<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture;

class Bar
{
    public function __construct(public readonly Foo $foo, public readonly string $property = 'test')
    {
    }
}
