<?php

declare(strict_types=1);

namespace Nyxio\Event;

class Event
{
    public const NAME = 'event';

    protected bool $isPropagationStopped = false;

    public function stopPropagation(): void
    {
        $this->isPropagationStopped = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }
}
