<?php

declare(strict_types=1);

namespace Nyxio\Helper\Url;

function normalizeUri(string $uri): string
{
    if ($uri === '/') {
        return '/';
    }

    $uri = \rtrim(\trim($uri), '/');

    if (isset($uri[0])) {
        if ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }
    } else {
        $uri = '/';
    }

    return $uri;
}
