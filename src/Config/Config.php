<?php

declare(strict_types=1);

namespace Nyxio\Config;

use function Nyxio\Helper\Directory\getAllFilesByDirectory;
use function Nyxio\Helper\Directory\join;

class Config extends MemoryConfig
{
    public function preload(): void
    {
        foreach (getAllFilesByDirectory(join($this->get('dir.root'), $this->get('dir.config'))) as $filename) {
            $filenameWithPath = join($this->get('dir.root'), $this->get('dir.config'), $filename);

            if (!\file_exists($filenameWithPath) || !\is_file($filenameWithPath)) {
                continue;
            }

            $this->addConfig(\str_replace('.php', '', $filename), require $filenameWithPath);
        }
    }
}
