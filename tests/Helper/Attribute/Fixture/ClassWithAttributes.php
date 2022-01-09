<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute\Fixture;

#[ClassAttribute('classAttribute')]
#[RepeatableClassAttribute('repeat1')]
#[RepeatableClassAttribute('repeat2')]
#[RepeatableClassAttribute('repeat3')]
class ClassWithAttributes extends ParentClassWithAttributes
{
    #[ConstAttribute('constAttribute')]
    public const TEST = 1;

    #[PropertyAttribute('propertyAttribute')]
    private bool $test;

    #[MethodAttribute('methodAttribute')]
    public function test(): void
    {
    }
}
