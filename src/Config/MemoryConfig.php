<?php

declare(strict_types=1);

namespace Nyxio\Config;

use Nyxio\Contract\Config\ConfigInterface;

class MemoryConfig implements ConfigInterface
{
    protected array $configs = [];

    public function addConfig(string $alias, array $configData): static
    {
        $this->configs[$alias] = $configData;

        return $this;
    }

    public function get(string $params, mixed $default = null): mixed
    {
        if (empty($params)) {
            throw new \InvalidArgumentException(\sprintf('Invalid config name "%s"', $params));
        }

        $parsedParams = \explode('.', $params);

        if (\count($parsedParams) === 1) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Incorrect use of config "%s". Use %s.your-option-name',
                    $params,
                    $params
                )
            );
        }

        $configName = \array_shift($parsedParams);

        if (!$this->has($configName)) {
            return $default;
        }

        $config = $this->configs[$configName];

        $value = $config;

        foreach ($parsedParams as $param) {
            if (!isset($value[$param])) {
                return $default;
            }

            $value = $value[$param];
        }

        return $value;
    }

    private function has(string $config): bool
    {
        return isset($this->configs[$config]);
    }
}
