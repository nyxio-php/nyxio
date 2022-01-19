<?php

declare(strict_types=1);

namespace Nyxio\Routing\Attribute;

use Nyxio\Contract\Http\Method;
use Nyxio\Routing\ValidatorTrait;

use function Nyxio\Helper\Url\joinUri;
use function Nyxio\Helper\Url\normalizeUri;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Route
{
    use ValidatorTrait;

    /**
     * @param Method $method
     * @param string $uri
     * @param array $rules
     */
    public function __construct(
        public readonly Method $method,
        protected string $uri,
        private readonly array $rules = [],
    ) {
        $this->uri = normalizeUri($uri);
        $this->createValidators($rules);
    }

    public function addPrefix(string $prefix): static
    {
        $this->uri = joinUri($prefix, $this->uri);

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
