<?php

declare(strict_types=1);

namespace Nyxio\Routing;

use function Nyxio\Helper\Url\normalizeUri;

class Group
{
    use ValidatorTrait;

    public function __construct(
        public readonly string $name,
        public ?string $prefix = null,
        public readonly array $rules = [],
        public readonly array $middlewares = [],
        public readonly array $validations = [],
        public readonly ?Group $parent = null,
    ) {
        if ($this->prefix !== null) {
            $this->prefix = normalizeUri($prefix);
        }

        $this->createFields($this->rules);
    }
}
