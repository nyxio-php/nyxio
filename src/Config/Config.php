<?php

declare(strict_types=1);

namespace Nyxio\Config;

use function Nyxio\Helper\Directory\join;

class Config extends MemoryConfig
{
    public function preloadConfigs(array $configs): Config
    {
        foreach ($configs as $config) {
            $this->loadConfig($config);
        }

        return $this;
    }

    private function loadConfig(string $config): void
    {
        $filename = join($this->get('dir.root'), $this->get('dir.config'), $config . '.php');

        if (\file_exists($filename) && \is_file($filename)) {
            $this->configs[$config] = require $filename;
        }
    }
}
