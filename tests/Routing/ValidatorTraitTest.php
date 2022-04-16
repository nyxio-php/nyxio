<?php

declare(strict_types=1);

namespace Nyxio\Tests\Routing;

use Nyxio\Routing\ValidatorTrait;
use Nyxio\Validation\Handler\Field;
use PHPUnit\Framework\TestCase;

class ValidatorTraitTest extends TestCase
{
    public function testBasic(): void
    {
        $class = new class () {
            use ValidatorTrait;
        };

        $validators = ['test' => new Field('attribute')];
        $class->appendValidators($validators);
        $this->assertEquals($validators, $class->getValidators());

        $validator = $class->getValidator('test');
        $this->assertInstanceOf(Field::class, $validator);
        $this->assertEquals('attribute', $validator->name);
    }
}
