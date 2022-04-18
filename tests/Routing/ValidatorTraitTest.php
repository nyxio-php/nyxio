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

        $fields = ['test' => new Field('attribute')];
        $class->appendFields($fields);
        $this->assertEquals($fields, $class->getFields());

        $validator = $class->getField('test');
        $this->assertInstanceOf(Field::class, $validator);
        $this->assertEquals('attribute', $validator->name);
    }
}
