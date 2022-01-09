<?php

declare(strict_types=1);

namespace Nyxio\Helper\Env;

use function Nyxio\Helper\Text\parseFromString;

function env(string $name, mixed $default = null): string|int|float|bool|null
{
    $value = \getenv($name);

    if ($value === false) {
        return $default;
    }

    return parseFromString($value);
}
