<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation\Handler;

use Nyxio\Validation\Handler\Field;

interface RulesCheckerInterface
{
    public function check(array $source, Field $field): array;
}
