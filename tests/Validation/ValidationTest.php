<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation;

use Nyxio\Container\Container;
use Nyxio\Contract\Validation\Rule;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Http\Exception\HttpException;
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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('firstName')->rule(Rule::String)->notNullable()->required();
        $validatorCollection->attribute('lastName')->rule(Rule::String)->notNullable()->notAllowsEmpty(
            'last name empty'
        );
        $validatorCollection->attribute('age')->rule(Rule::Integer)->nullable()->required();
        $validatorCollection->attribute('email')->rule(Rule::Email)->notNullable()->required('EMPTY EMAIL');

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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('firstName')->rule('asdasd');
        $validatorCollection->attribute('lastName')->rule('dgadgadg');
        $validatorCollection->attribute('age')->rule('egegeqg')->nullable();
        $validatorCollection->attribute('email')->rule('egeqgeqg');

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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('firstName')->rule('string', message: 'First name can be only string!');
        $validatorCollection->attribute('age')->nullable()->rule(
            rule: 'integer',
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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo')->notAllowsEmpty('empty error');
        $validatorCollection->attribute('bar')->allowsEmpty();
        $validatorCollection->attribute('code')->allowsEmpty();

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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo.bar')->required('bar is required');
        $validatorCollection->attribute('bar.foo.test')->required('test is required');
        $validatorCollection->attribute('code')->notRequired();

        $this->assertEquals(
            [
                'bar.foo.test' => ['test is required'],
            ],
            $validatorCollection->getErrors($data)
        );
    }

    public function testNullable(): void
    {
        $data = [
            'foo' => null,
            'bar' => null,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo')->notNullable('null error');
        $validatorCollection->attribute('bar')->nullable();
        $validatorCollection->attribute('code')->nullable();

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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo')->required('EMPTY');
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
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo')->required('EMPTY');
        $this->assertTrue($validatorCollection->validateOrException($data));
    }

    public function testRuleGroup(): void
    {
        $data = [
            'foo' => true,
        ];

        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(RulesWithGroup::class);
        $rulesChecker = new RulesChecker($executorCollection);
        $validatorCollection = new ValidatorCollection($rulesChecker);

        $validatorCollection->attribute('foo')->rule('test:test');
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
