<?php

declare(strict_types=1);

namespace Nyxio\Http\Factory;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response(status: $code, reason: $reasonPhrase);
    }
}
