<?php

declare(strict_types=1);

namespace Nyxio\Tests\Provider\Fixture;

class NotProvider
{
    public function __construct(public bool $invoked = false)
    {
    }

    public function process(): void
    {
        $this->invoked = true;
    }
}
