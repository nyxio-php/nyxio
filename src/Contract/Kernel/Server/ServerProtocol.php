<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Server;

enum ServerProtocol: string
{
    case HTTP = 'http';
    case WebSocket = 'ws';
}