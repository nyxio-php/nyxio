<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

use Nyxio\Contract\Validation\Rule;

class Field
{
    private array $rules = [];

    private bool $isNullable = false;

    private bool $required = false;

    private bool $allowsEmpty = true;

    private string $requiredMessage = 'validation.field_is_required';

    private string $nullMessage = 'validation.field_cannot_be_null';

    private string $allowsEmptyMessage = 'validation.field_cannot_be_empty';

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

    public function isInteger(?string $message = null): static
    {
        return $this->rule(Rule::Integer, message: $message);
    }

    public function isString(?string $message = null): static
    {
        return $this->rule(Rule::String, message: $message);
    }

    public function isBool(?string $message = null): static
    {
        return $this->rule(Rule::Bool, message: $message);
    }

    public function isNumeric(?string $message = null): static
    {
        return $this->rule(Rule::Numeric, message: $message);
    }

    public function isFloat(?string $message = null): static
    {
        return $this->rule(Rule::Float, message: $message);
    }

    public function isArray(?string $message = null): static
    {
        return $this->rule(Rule::Array, message: $message);
    }

    public function isEmail(?string $message = null): static
    {
        return $this->rule(Rule::Email, message: $message);
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
