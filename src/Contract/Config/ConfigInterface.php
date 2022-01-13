<?php

declare(strict_types=1);

namespace Nyxio\Contract\Config;

interface ConfigInterface
{
    public function get(string $name, mixed $default = null): mixed;

    public function addConfig(string $alias, array $configData): static;
}
