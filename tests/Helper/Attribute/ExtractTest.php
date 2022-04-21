<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Attribute;

use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Tests\Helper\Attribute\Fixture\ClassAttribute;
use Nyxio\Tests\Helper\Attribute\Fixture\ClassWithAttributes;
use Nyxio\Tests\Helper\Attribute\Fixture\ConstAttribute;
use Nyxio\Tests\Helper\Attribute\Fixture\MethodAttribute;
use Nyxio\Tests\Helper\Attribute\Fixture\PropertyAttribute;
use Nyxio\Tests\Helper\Attribute\Fixture\RepeatableClassAttribute;
use PHPUnit\Framework\TestCase;

class ExtractTest extends TestCase
{
    /**
     * @param mixed $except
     * @param mixed $result
     * @return void
     *
     * @dataProvider getExtractSingleAttributeDataProvider
     */
    public function testExtractSingleAttribute(mixed $except, mixed $result): void
    {
        if ($except === null) {
            $this->assertNull($except);
        } else {
            $this->assertInstanceOf(
                $except,
                $result,
            );
        }
    }

    public function testInvalidSource(): void
    {
        $helper = new ExtractAttribute();

        $this->expectException(\InvalidArgumentException::class);
        $this->assertNull($helper->all('asdas', 'test'));
    }

    public function testInvalidSource2(): void
    {
        $helper = new ExtractAttribute();

        $this->expectException(\InvalidArgumentException::class);
        $this->assertNull($helper->first('asdas', 'test'));
    }

    public function testParent(): void
    {
        $helper = new ExtractAttribute();

        $this->assertCount(
            4,
            $helper->all(ClassWithAttributes::class, RepeatableClassAttribute::class, parent: true)
        );
    }

    public function testParentByMethod(): void
    {
        $helper = new ExtractAttribute();

        $reflection = new \ReflectionClass(ClassWithAttributes::class);

        $this->assertCount(
            2,
            $helper->all($reflection->getMethod('test'), MethodAttribute::class, parent: true)
        );
    }

    public function testParentByProperty(): void
    {
        $helper = new ExtractAttribute();

        $reflection = new \ReflectionClass(ClassWithAttributes::class);

        $this->assertCount(
            1,
            $helper->all($reflection->getProperty('test'), PropertyAttribute::class, parent: true)
        );
    }

    public function testParentByConst(): void
    {
        $helper = new ExtractAttribute();

        $reflection = new \ReflectionClass(ClassWithAttributes::class);
        $constant = $reflection->getReflectionConstant('TEST');
        $this->assertInstanceOf(\ReflectionClassConstant::class, $constant);

        $this->assertCount(
            1,
            $helper->all($constant, ConstAttribute::class, parent: true)
        );
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testParentByInvalidType(): void
    {
        $helper = new ExtractAttribute();

        $function = static fn() => 'test';

        $reflection = new \ReflectionFunction($function);

        $this->assertCount(
            0,
            $helper->all($reflection, MethodAttribute::class, parent: true)
        );
    }

    public function testInvalidArgument(): void
    {
        $helper = new ExtractAttribute();

        $this->expectException(\InvalidArgumentException::class);
        $helper->first(new \ReflectionNamedType(), MethodAttribute::class);
    }

    private function getExtractSingleAttributeDataProvider(): \Generator
    {
        $helper = new ExtractAttribute();
        $reflection = new \ReflectionClass(ClassWithAttributes::class);

        yield [
            ClassAttribute::class,
            $helper->first(ClassWithAttributes::class, ClassAttribute::class),
        ];

        yield [
            ConstAttribute::class,
            $helper->first(
                $reflection->getReflectionConstant('TEST'),
                ConstAttribute::class
            ),
        ];

        yield [
            PropertyAttribute::class,
            $helper->first($reflection->getProperty('test'), PropertyAttribute::class),
        ];

        yield [
            MethodAttribute::class,
            $helper->first($reflection->getMethod('test'), MethodAttribute::class),
        ];

        yield [
            null,
            $helper->first($reflection->getMethod('test'), 'invalid attribute'),
        ];
    }
}
