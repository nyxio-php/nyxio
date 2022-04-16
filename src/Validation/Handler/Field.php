<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

class Field
{
    private array $rules = [];

    private bool $isNullable = false;

    private bool $required = false;

    private bool $allowsEmpty = true;

    private string $requiredMessage = 'field_is_required';

    private string $nullMessage = 'field_cannot_be_null';

    private string $allowsEmptyMessage = 'field_cannot_be_empty';

    public function __construct(public readonly string $name)
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

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isAllowsEmpty(): bool
    {
        return $this->allowsEmpty;
    }

    public function required(?string $message = null): static
    {
        if ($message !== null) {
            $this->requiredMessage = $message;
        }

        $this->required = true;

        return $this;
    }

    public function notRequired(): static
    {
        $this->required = false;

        return $this;
    }

    public function notAllowsEmpty(?string $message = null): static
    {
        if ($message !== null) {
            $this->allowsEmptyMessage = $message;
        }

        $this->allowsEmpty = false;

        return $this;
    }

    public function allowsEmpty(): static
    {
        $this->allowsEmpty = true;

        return $this;
    }

    public function getAllowsEmptyMessage(): string
    {
        return $this->allowsEmptyMessage;
    }

    public function getRequiredMessage(): string
    {
        return $this->requiredMessage;
    }

    public function getNullableMessage(): string
    {
        return $this->nullMessage;
    }

    public function rule(string|\BackedEnum $rule, array $parameters = [], ?string $message = null): static
    {
        $this->rules[$rule instanceof \BackedEnum ? $rule->value : $rule] = [
            'params' => $parameters,
            'message' => $message,
        ];

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
