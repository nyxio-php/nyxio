<?php

declare(strict_types=1);

namespace Nyxio\Container;

class Injection
{
    private array $arguments = [];

    private object|null $instance = null;

    public function __construct(public readonly string $name, public readonly object|string $classNameOrInstance)
    {
        if (\is_object($this->classNameOrInstance)) {
            $this->instance = $this->classNameOrInstance;
        }
    }

    public function hasInstance(): bool
    {
        return \is_object($this->instance);
    }

    public function addInstance(object $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    public function getInstance(): ?object
    {
        return $this->instance;
    }

    public function getArgument(string $key): mixed
    {
        return $this->arguments[$key] ?? null;
    }

    public function hasArgument(string $key): bool
    {
        return \array_key_exists($key, $this->arguments);
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function addArgument(string $name, mixed $value): static
    {
        $this->arguments[$name] = $value;

        return $this;
    }
}
