<?php

declare(strict_types=1);

namespace Nyxio\Tests\Provider\Fixture;

use Nyxio\Contract\Provider\ProviderInterface;

class TestProvider implements ProviderInterface
{
    public function __construct(public bool $invoked = false)
    {
    }

    public function process(): void
    {
        $this->invoked = true;
    }
}
