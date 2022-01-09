<?php

declare(strict_types=1);

namespace Nyxio\Contract\Provider;

interface ProviderInterface
{
    /**
     * Process provider
     * @return void
     */
    public function process(): void;
}
