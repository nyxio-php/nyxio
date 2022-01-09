<?php

declare(strict_types=1);

namespace Nyxio\Contract\Event;

use Nyxio\Event\Event;

interface EventDispatcherInterface
{
    public function addListener(string $eventName, \Closure|array $listener): static;

    public function getListeners(string $eventName): iterable;

    public function dispatch(string $eventName, ?Event $event = null): static;
}
