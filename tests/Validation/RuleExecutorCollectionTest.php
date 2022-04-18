<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation;

use Nyxio\Container\Container;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Tests\Validation\Fixture\RulesWithoutAttribute;
use Nyxio\Validation\Helper\DefaultRules;
use Nyxio\Validation\RuleExecutorCollection;
use PHPUnit\Framework\TestCase;

class RuleExecutorCollectionTest extends TestCase
{
    public function testRegisterRulesWithoutAttribute(): void
    {
        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(RulesWithoutAttribute::class);

        $this->assertEmpty($executorCollection->all());
    }

    public function testRegisterInvalidClassName(): void
    {
        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $this->expectException(\RuntimeException::class);
        $executorCollection->register('tik');
    }

    public function testHas(): void
    {
        $executorCollection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $executorCollection->register(DefaultRules::class);
        $this->assertTrue($executorCollection->has('string'));
    }
}
