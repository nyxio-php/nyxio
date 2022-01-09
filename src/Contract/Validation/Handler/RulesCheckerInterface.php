<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation\Handler;

use Nyxio\Validation\Handler\Validator;

interface RulesCheckerInterface
{
    public function check(array $source, Validator $validator): array;
}
