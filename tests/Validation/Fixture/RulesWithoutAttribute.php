<?php

declare(strict_types=1);

namespace Nyxio\Tests\Validation\Fixture;

class RulesWithoutAttribute
{
    public function string(mixed $value): bool
    {
        return false;
    }
}
