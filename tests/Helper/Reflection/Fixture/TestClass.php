<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Reflection\Fixture;

class TestClass
{
    public function methodOne(TestItem $item): void
    {
    }

    public function methodTwo(TestItem|TestPrice $item): void
    {
    }

    public function methodThree(): void
    {
    }
}
