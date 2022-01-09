<?php

declare(strict_types=1);

namespace Nyxio\Event;

use Nyxio\Contract\Event\EventDispatcherInterface;

class Dispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function addListener(string $eventName, \Closure|array $listener): static
    {
        if (!\is_callable($listener)) {
            throw new \InvalidArgumentException('Listener is not callable');
        }

        $this->listeners[$eventName][] = $listener;

        return $this;
    }

    public function getListeners(string $eventName): iterable
    {
        return $this->listeners[$eventName] ?? [];
    }

    public function dispatch(string $eventName, ?Event $event = null): static
    {
        /** @var \Closure|array $listener */
        foreach ($this->listeners[$eventName] ?? [] as $listener) {
            try {
                $listener($event);
            } catch (\Throwable) {
                break;
            }

            if ($event instanceof Event && $event->isPropagationStopped()) {
                break;
            }
        }

        return $this;
    }
}
