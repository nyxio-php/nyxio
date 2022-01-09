<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

class Validator
{
    private array $rules = [];

    private bool $isNullable = false;

    private bool $allowsEmpty = true;

    private string $emptyMessage = 'Attribute cannot be empty';

    private string $nullMessage = 'Attribute cannot be null';

    public function __construct(public readonly string $attribute)
    {
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function notNullable(?string $message = null): static
    {
        if ($message !== null) {
            $this->nullMessage = $message;
        }

        $this->isNullable = false;

        return $this;
    }

    public function nullable(): static
    {
        $this->isNullable = true;

        return $this;
    }

    public function isAllowsEmpty(): bool
    {
        return $this->allowsEmpty;
    }

    public function notAllowsEmpty(?string $message = null): static
    {
        if ($message !== null) {
            $this->emptyMessage = $message;
        }

        $this->allowsEmpty = false;

        return $this;
    }

    public function allowsEmpty(): static
    {
        $this->allowsEmpty = true;

        return $this;
    }

    public function getEmptyMessage(): string
    {
        return $this->emptyMessage;
    }

    public function getNullableMessage(): string
    {
        return $this->nullMessage;
    }

    public function rule(string $rule, array $parameters = []): static
    {
        $this->rules[$rule] = $parameters;

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
