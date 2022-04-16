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

    return match (\mb_strtolower($value)) {
        'null' => null,
        'true' => true,
        'false' => false,
        default => $value,
    };
}

function getFormattedText(string $source, array $params = []): string
{
    $params = \array_filter(
        $params,
        static fn(mixed $param) => !(\is_array($param) && count($param) !== count(
                    $param,
                    \COUNT_RECURSIVE
                )) && !\is_object($param)
    );

    $params = \array_map(
        static function (mixed $param) {
            if (\is_array($param)) {
                return \implode(', ', $param);
            }

            if (\is_bool($param)) {
                return $param ? 'true' : 'false';
            }

            return $param;
        },
        $params,
    );

    return \str_replace(
        \array_map(static fn($value) => ':' . $value, \array_keys($params)),
        $params,
        $source
    );
}
