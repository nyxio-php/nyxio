<?php

declare(strict_types=1);

namespace Nyxio\Http\Factory;

use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use function Nyxio\Helper\Text\parseFromString;
use function Nyxio\Helper\Url\normalizeUri;

class RequestFactory implements ServerRequestFactoryInterface
{
    public function __construct(private readonly UriFactoryInterface $uriFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (!$uri instanceof UriInterface) {
            $uri = $this->uriFactory->createUri(
                normalizeUri($uri)
            );
        }

        return $this->attachQuery(
            $uri,
            new ServerRequest(method: $method, uri: $uri, serverParams: $serverParams)
        );
    }

    private function attachQuery(UriInterface $uri, ServerRequest $request): ServerRequest
    {
        if (empty($uri->getQuery())) {
            return $request;
        }

        \parse_str($uri->getQuery(), $parsedQuery);

        return $request->withQueryParams(
            \array_map(
                static fn(string $value) => parseFromString($value),
                $parsedQuery
            )
        );
    }
}
