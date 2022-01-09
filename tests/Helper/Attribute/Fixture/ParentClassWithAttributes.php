<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute\Fixture;

#[RepeatableClassAttribute('repeat0')]
class ParentClassWithAttributes
{
    #[MethodAttribute('methodAttribute0')]
    public function test(): void
    {
    }
}
