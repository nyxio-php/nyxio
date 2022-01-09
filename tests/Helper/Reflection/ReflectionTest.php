<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Reflection;

use Nyxio\Tests\Helper\Reflection\Fixture\TestClass;
use Nyxio\Tests\Helper\Reflection\Fixture\TestItem;
use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Reflection\getMethodParametersNames;

class ReflectionTest extends TestCase
{
    public function testBasic(): void
    {
        $reflection = new \ReflectionClass(TestClass::class);
        $methodOne = $reflection->getMethod('methodOne');
        $methodTwo = $reflection->getMethod('methodTwo');
        $methodThree = $reflection->getMethod('methodThree');

        $this->assertEquals(
            [
                'item' => TestItem::class,
            ],
            getMethodParametersNames($methodOne)
        );

        $this->assertEmpty(getMethodParametersNames($methodTwo));
        $this->assertEmpty(getMethodParametersNames($methodThree));
    }
}
