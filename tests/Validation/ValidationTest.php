<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Validation\Rule;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Http\Exception\HttpException;
use Nyxio\Kernel\Text\Message;
use Nyxio\Tests\Validation\Fixture\RulesWithGroup;
use Nyxio\Validation\Attribute\Validation;
use Nyxio\Validation\DefaultRules;
use Nyxio\Validation\Handler\RulesChecker;
use Nyxio\Validation\Handler\ValidatorCollection;
use Nyxio\Validation\RuleExecutorCollection;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    public function testBasic(): void
    {
        $collection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $collection->register(DefaultRules::class);
        $this->assertTrue($collection->get('string')->validate(['value' => 'asdf', 'a' => 1]));
        $this->assertFalse($collection->get('string')->validate(['value' => false]));
    }

    public function testValidation(): void
    {
        $data = [
            'firstName' => 'Alex',
            'lastName' => '',
            'age' => null,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('firstName')->isString()->notNullable()->required();
        $validatorCollection->field('lastName')->isString()->notNullable()->notAllowsEmpty(
            'last name empty'
        );
        $validatorCollection->field('age')->isInteger()->nullable()->required();
        $validatorCollection->field('email')->isEmail()->notNullable()->required('EMPTY EMAIL');

        $this->assertEquals(
            [
                'email' => [
                    'EMPTY EMAIL',
                ],
                'lastName' => [
                    'last name empty',
                ],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testInvalidRules(): void
    {
        $data = [
            'firstName' => 'Alex',
            'lastName' => '',
            'age' => null,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('firstName')->rule('asdasd');
        $validatorCollection->field('lastName')->rule('dgadgadg');
        $validatorCollection->field('age')->rule('egegeqg')->nullable();
        $validatorCollection->field('email')->rule('egeqgeqg');

        $this->assertEquals(
            [
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testCustomRule(): void
    {
        $data = [
            'firstName' => 'Alex',
            'lastName' => '',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('firstName')->custom(static function (mixed $value): bool {
            return $value === 'Alex';
        }, 'Invalid first name');

        $validatorCollection->field('lastName')->custom(static function (mixed $value): bool {
            return !empty($value);
        }, 'Invalid :field: cannot be empty');

        $this->assertEquals(
            [
                'lastName' => [
                    'Invalid lastName: cannot be empty',
                ]
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testResetCustomRules(): void
    {
        $data = [
            'firstName' => 'Alex',
            'lastName' => '',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('firstName')->custom(static function (mixed $value): bool {
            return $value === 'Alex';
        }, 'Invalid first name');

        $field = $validatorCollection->field('lastName')->custom(static function (mixed $value): bool {
            return !empty($value);
        }, 'Invalid :field: cannot be empty');

        $field->resetCustomRules();

        $this->assertEquals(
            [
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testCustomMessages(): void
    {
        $data = [
            'firstName' => 'Alex',
            'age' => '25.4',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('firstName')->isString(message: 'First name can be only string!');
        $validatorCollection->field('age')->nullable()->rule(
            rule:    'integer',
            message: 'Age can be only integer or null!'
        );

        $this->assertEquals(
            [
                'age' => ['Age can be only integer or null!'],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testAllowsEmpty(): void
    {
        $data = [
            'foo' => '',
            'bar' => '',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo')->notAllowsEmpty('empty error');
        $validatorCollection->field('bar')->allowsEmpty();
        $validatorCollection->field('code')->allowsEmpty();

        $this->assertEquals(
            [
                'foo' => ['empty error'],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testRequired(): void
    {
        $data = [
            'foo' => [
                'bar' => 1,
            ],
            'bar' => [],
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo.bar')->required('bar is required');
        $validatorCollection->field('bar.foo.test')->required('test is required');
        $validatorCollection->field('code')->notRequired();

        $this->assertEquals(
            [
                'bar.foo.test' => ['test is required'],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testRemoveRule(): void
    {
        $data = [
            'test' => 'asdf',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $field = $validatorCollection->field('test')->isInteger();
        $this->assertTrue($field->hasRule(Rule::Integer));
        $field->removeRule(Rule::Integer);

        $this->assertEquals(
            [
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testTypes(): void
    {
        $data = [
            'float' => 1.1,
            'integer' => 1,
            'bool' => false,
            'string' => 'test',
            'numeric1' => '1',
            'numeric2' => 1,
            'numeric3' => 1.1,
            'array' => ['a', 'b'],
            'email' => 'test@gmail.com',
            'url' => 'https://github.com',
            'dateTime' => '2022-04-18T09:24:12+00:00',
            'date' => '2022-04-18',
            'time' => 'T09:24:12+00:00',
            'altDateTime' => '2022-04-18 09:24:12',
            'altDate' => '04-18-2022',
            'altTime' => '04:22',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('float')->isFloat();
        $validatorCollection->field('integer')->isInteger();
        $validatorCollection->field('bool')->isBool();
        $validatorCollection->field('string')->isString();
        $validatorCollection->field('numeric1')->isNumeric();
        $validatorCollection->field('numeric2')->isNumeric();
        $validatorCollection->field('numeric3')->isNumeric();
        $validatorCollection->field('array')->isArray();
        $validatorCollection->field('email')->isEmail();
        $validatorCollection->field('url')->isUrl();
        $validatorCollection->field('dateTime')->isDateTime();
        $validatorCollection->field('date')->isDate();
        $validatorCollection->field('time')->isTime();
        $validatorCollection->field('altDateTime')->isDateTime('Y-m-d H:i:s');
        $validatorCollection->field('altDate')->isDate(format: 'm-d-Y');
        $validatorCollection->field('altTime')->isTime(format: 'H:i');

        $this->assertEquals(
            [
            ],
            $validatorCollection->getErrors($data)
        );

        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('float')->isInteger();
        $validatorCollection->field('integer')->isFloat();
        $validatorCollection->field('bool')->isString();
        $validatorCollection->field('string')->isInteger();
        $validatorCollection->field('numeric1')->isFloat();
        $validatorCollection->field('numeric2')->isBool();
        $validatorCollection->field('numeric3')->isArray();
        $validatorCollection->field('array')->isEmail();
        $validatorCollection->field('email')->isUrl();
        $validatorCollection->field('url')->isDate();
        $validatorCollection->field('dateTime')->isUrl();
        $validatorCollection->field('date')->isTime();
        $validatorCollection->field('time')->isDate();
        $validatorCollection->field('altDateTime')->isDateTime();
        $validatorCollection->field('altDate')->isDate();
        $validatorCollection->field('altTime')->isTime();

        $this->assertCount(count($data), $validatorCollection->getErrors($data));
    }

    public function testNullable(): void
    {
        $data = [
            'foo' => null,
            'bar' => null,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo')->notNullable('null error');
        $validatorCollection->field('bar')->nullable();
        $validatorCollection->field('code')->nullable();

        $this->assertEquals(
            [
                'foo' => ['null error'],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testException(): void
    {
        $data = [];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo')->required('EMPTY');
        $this->expectException(HttpException::class);
        $validatorCollection->validateOrException($data);
    }

    /**
     * @return void
     * @throws HttpException
     */
    public function testException2(): void
    {
        $data = [
            'foo' => 'test',
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo')->required('EMPTY');
        $this->assertTrue($validatorCollection->validateOrException($data));
    }

    public function testRuleGroup(): void
    {
        $data = [
            'foo' => true,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(RulesWithGroup::class);
        $rulesChecker = new RulesChecker($executorCollection, new Message(new MemoryConfig()));
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->field('foo')->rule('test:test');
        $this->assertEmpty($validatorCollection->getErrors($data));
    }

    public function testValidationAttribute(): void
    {
        $attribute = new Validation('test');

        $this->assertEquals('test', $attribute->name);
    }

    public function testInvalidArgumentsRuleExecutor(): void
    {
        $collection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $collection->register(DefaultRules::class);

        $this->assertFalse($collection->get('array')->validate(['test']));
    }
}
