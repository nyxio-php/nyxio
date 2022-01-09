<?php

declare(strict_types=1);

namespace Nyxio\Tests\Event\Fixture;

class Listener
{
    private bool $invoked = false;

    public function invoke(): void
    {
        $this->invoked = true;
    }

    public function isInvoked(): bool
    {
        return $this->invoked;
    }
}
