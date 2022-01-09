<?php

declare(strict_types=1);

namespace Nyxio\Helper\Directory;

function join(...$paths): string
{
    $basePath = rtrim(\array_shift($paths), \DIRECTORY_SEPARATOR);

    \array_walk($paths, static function (string &$item) {
        $item = \rtrim(\ltrim($item, \DIRECTORY_SEPARATOR), \DIRECTORY_SEPARATOR);
    });

    return \implode(\DIRECTORY_SEPARATOR, \array_merge([$basePath], $paths));
}
