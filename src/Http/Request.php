<?php

declare(strict_types=1);

namespace Nyxio\Http;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    public function __construct(protected ServerRequestInterface $request)
    {
    }

    public function get(?string $name = null, mixed $default = null): mixed
    {
        if ($name === null) {
            return $this->request->getQueryParams();
        }

        return \array_key_exists($name, $this->request->getQueryParams())
            ? $this->request->getQueryParams()[$name]
            : $default;
    }

    public function post(?string $name = null, mixed $default = null): mixed
    {
        if (!\is_array($this->request->getParsedBody())) {
            return [];
        }

        if ($name === null) {
            return $this->request->getParsedBody();
        }

        return array_key_exists($name, $this->request->getParsedBody())
            ? $this->request->getParsedBody()[$name]
            : $default;
    }

    public function cookie(?string $name = null, mixed $default = null): mixed
    {
        if ($name === null) {
            return $this->request->getCookieParams();
        }

        return \array_key_exists($name, $this->request->getCookieParams())
            ? $this->request->getCookieParams()[$name]
            : $default;
    }

    public function header(?string $name = null, mixed $default = null): mixed
    {
        if ($name === null) {
            return \array_merge_recursive(
                ...
                \array_map(
                    fn(string $name) => [$name => $this->header($name)],
                    \array_keys($this->request->getHeaders())
                )
            );
        }

        if (!$this->request->hasHeader($name)) {
            return $default;
        }

        $headers = $this->request->getHeader($name);

        return \count($headers) > 1 ? $headers : $headers[0];
    }

    public function server(?string $name = null, mixed $default = null): mixed
    {
        if ($name === null) {
            return $this->request->getServerParams();
        }

        return \array_key_exists($name, $this->request->getServerParams())
            ? $this->request->getServerParams()[$name]
            : $default;
    }
}
