<?php

declare(strict_types=1);

namespace Nyxio\Contract\Http;

enum ContentType: string
{
    case Html = 'text/html';
    case Json = 'application/json';
    case Xml = 'application/xhtml+xml';
}
