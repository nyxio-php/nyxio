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

function getAllFilesByDirectory(string $directory): array
{
    $directoryFiles = array_diff(scandir($directory), ['.', '..']);

    $files = [];

    foreach ($directoryFiles as $filename) {
        if (is_dir($directory . \DIRECTORY_SEPARATOR . $filename)) {
            foreach (getAllFilesByDirectory($directory . \DIRECTORY_SEPARATOR . $filename) as $directoryFilename) {
                $files[] = $filename . \DIRECTORY_SEPARATOR . $directoryFilename;
            }
            continue;
        }

        $files[] = $filename;
    }

    return $files;
}
