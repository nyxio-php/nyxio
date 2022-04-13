<?php

declare(strict_types=1);

namespace Nyxio\Contract\Server;

enum ServerProtocol: string
{
    case HTTP = 'HTTP';
    case WebSocket = 'WebSocket';
}
