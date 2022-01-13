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

    public function get(string $name, mixed $default = null): mixed
    {
        if (empty($name)) {
            throw new \InvalidArgumentException(\sprintf('Invalid config name "%s"', $name));
        }

        $nameKeys = \explode('.', $name);

        if (\count($nameKeys) === 1) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Incorrect use of config "%s". Use %s.your-option-name',
                    $name,
                    $name
                )
            );
        }

        $configName = \array_shift($nameKeys);

        if (!$this->has($configName)) {
            return $default;
        }

        $value = $this->configs[$configName];

        foreach ($nameKeys as $nameKey) {
            if (!\array_key_exists($nameKey, $value)) {
                return $default;
            }

            $value = $value[$nameKey];
        }

        return $value;
    }

    private function has(string $config): bool
    {
        return isset($this->configs[$config]);
    }
}
