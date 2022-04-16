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

        $this->assertEquals(true, $collection->get('min')->validate(['value' => 10, 'min' => 2]));
        $this->assertEquals(false, $collection->get('min')->validate(['value' => 15, 'min' => 20]));
        $this->assertEquals(false, $collection->get('min')->validate(['value' => 'asfadg', 'min' => 20]));

        $this->assertEquals(true, $collection->get('max')->validate(['value' => 10, 'max' => 11]));
        $this->assertEquals(false, $collection->get('max')->validate(['value' => 12, 'max' => 11]));
        $this->assertEquals(false, $collection->get('max')->validate(['value' => 'asdf', 'max' => 11]));
        $this->assertEquals(true, $collection->get('max')->validate(['value' => '10', 'max' => 11]));

        $this->assertEquals(true, $collection->get('enum')->validate(['value' => 'test', 'enum' => ['test', 'test2']]));
        $this->assertEquals(
            false,
            $collection->get('enum')->validate(['value' => 'test', 'enum' => ['test1', 'test2']])
        );
        $this->assertEquals(true, $collection->get('enum')->validate(['value' => 1, 'enum' => [1, 2, 3]]));
        $this->assertEquals(
            false,
            $collection->get('enum')->validate(['value' => '1', 'enum' => [1, 2, 3], 'strict' => true])
        );
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
