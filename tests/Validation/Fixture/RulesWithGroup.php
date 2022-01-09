<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation\Fixture;

use Nyxio\Validation\Attribute\Rule;
use Nyxio\Validation\Attribute\RuleGroup;

#[RuleGroup('test')]
class RulesWithGroup
{
    #[Rule('test', 'TEST MESSAGE')]
    public function test(mixed $value): bool
    {
        return $value;
    }
}
