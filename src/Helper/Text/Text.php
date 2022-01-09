<?php

declare(strict_types=1);

namespace Nyxio\Helper\Text;

function parseFromString(string $value): string|int|float|bool|null
{
    if (\is_numeric($value)) {
        if (\str_contains($value, '.')) {
            return (float)$value;
        }

        return (int)$value;
    }

    $lowerCaseValue = \mb_strtolower($value);

    if ($lowerCaseValue === 'null') {
        return null;
    }

    if ($lowerCaseValue === 'true') {
        return true;
    }

    if ($lowerCaseValue === 'false') {
        return false;
    }

    return $value;
}

function getFormattedText(string $source, array $params = []): string
{
    $params = \array_filter($params,  static fn (mixed $param) => !\is_array($param));

    return \str_replace(
        \array_map(static fn ($value) => ':' . $value, \array_keys($params)),
        $params,
        $source
    );
}
