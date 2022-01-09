<?php

declare(strict_types=1);

namespace Nyxio\Http;

use Nyxio\Contract\Http\ContentType;
use Psr\Http\Message\ResponseInterface;

class Response
{
    public function __construct(protected ResponseInterface $response)
    {
    }

    public function json(string|array|int|bool|float|\JsonSerializable $content, ?int $code = null): ResponseInterface
    {
        $response = $this->response;

        $response = $response->withHeader('Content-Type', ContentType::Json->value);

        if ($code !== null) {
            $response = $response->withStatus($code);
        }

        try {
            $response->getBody()->write(\json_encode($content, JSON_THROW_ON_ERROR));
        } catch (\Throwable) {
        }

        return $this->response = $response;
    }
}
