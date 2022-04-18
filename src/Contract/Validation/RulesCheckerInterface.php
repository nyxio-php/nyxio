<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation;

use Nyxio\Validation\DTO\Field;

interface RulesCheckerInterface
{
    public function check(array $source, Field $field): array;
}
