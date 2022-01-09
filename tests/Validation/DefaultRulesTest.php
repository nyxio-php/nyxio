<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation;

use Nyxio\Container\Container;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Validation\DefaultRules;
use Nyxio\Validation\RuleExecutorCollection;
use PHPUnit\Framework\TestCase;

class DefaultRulesTest extends TestCase
{
    public function testBasic(): void
    {
        $collection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $collection->register(DefaultRules::class);

        $this->assertEquals(true, $collection->get('array')->validate(['value' => ['test']]));
        $this->assertEquals(false, $collection->get('array')->validate(['value' => false]));


        $this->assertEquals(true, $collection->get('string')->validate(['value' => 'test']));
        $this->assertEquals(false, $collection->get('string')->validate(['value' => 1]));

        $this->assertEquals(true, $collection->get('integer')->validate(['value' => 1]));
        $this->assertEquals(false, $collection->get('integer')->validate(['value' => '1']));
        $this->assertEquals(false, $collection->get('integer')->validate(['value' => 'test']));

        $this->assertEquals(true, $collection->get('numeric')->validate(['value' => 1]));
        $this->assertEquals(true, $collection->get('numeric')->validate(['value' => '1']));
        $this->assertEquals(false, $collection->get('numeric')->validate(['value' => 'test']));

        $this->assertEquals(true, $collection->get('float')->validate(['value' => 1.0]));
        $this->assertEquals(false, $collection->get('float')->validate(['value' => 1]));
        $this->assertEquals(false, $collection->get('float')->validate(['value' => '1']));
        $this->assertEquals(false, $collection->get('float')->validate(['value' => 'test']));

        $this->assertEquals(true, $collection->get('bool')->validate(['value' => true]));
        $this->assertEquals(false, $collection->get('bool')->validate(['value' => '1']));
        $this->assertEquals(false, $collection->get('bool')->validate(['value' => 'false']));

        $this->assertEquals(true, $collection->get('email')->validate(['value' => 'test@mail.com']));
        $this->assertEquals(false, $collection->get('email')->validate(['value' => '1']));
        $this->assertEquals(false, $collection->get('email')->validate(['value' => 'test@test@test.com']));

        $this->assertEquals(true, $collection->get('max-len')->validate(['value' => 'test@mail.com', 'max' => 13]));
        $this->assertEquals(false, $collection->get('max-len')->validate(['value' => 'test@mail.com', 'max' => 5]));

        $this->assertEquals(true, $collection->get('min-len')->validate(['value' => 'test@mail.com', 'min' => 13]));
        $this->assertEquals(false, $collection->get('min-len')->validate(['value' => 'test@mail.com', 'min' => 20]));
    }

    public function testInvalidArgumentMinLength(): void
    {
        $rules = new DefaultRules();
        $this->expectException(\InvalidArgumentException::class);
        $rules->minLength('test', -1);
    }

    public function testInvalidArgumentMaxLength(): void
    {
        $rules = new DefaultRules();
        $this->expectException(\InvalidArgumentException::class);
        $rules->maxLength('test', 0);
    }
}
