<?php

declare(strict_types=1);

namespace Nyxio\Validation;

use Nyxio\Contract\Validation\Rule as RuleEnum;
use Nyxio\Validation\Attribute\Rule;

class DefaultRules
{
    #[Rule(RuleEnum::String, 'field_is_not_string')]
    public function string(mixed $value): bool
    {
        return \is_string($value);
    }

    #[Rule(RuleEnum::Integer, 'field_is_not_int')]
    public function integer(mixed $value): bool
    {
        return \is_int($value);
    }

    #[Rule(RuleEnum::Numeric, 'field_is_not_numeric')]
    public function numeric(mixed $value): bool
    {
        return \is_numeric($value);
    }

    #[Rule(RuleEnum::Float, 'field_is_not_float')]
    public function float(mixed $value): bool
    {
        return \is_float($value);
    }

    #[Rule(RuleEnum::Bool, 'field_is_not_boolean')]
    public function bool(mixed $value): bool
    {
        return \is_bool($value);
    }

    #[Rule(RuleEnum::Array, 'field_is_not_array')]
    public function array(mixed $value): bool
    {
        return \is_array($value);
    }

    #[Rule(RuleEnum::Email, 'field_is_not_email')]
    public function email(mixed $value): bool
    {
        return \filter_var($value, \FILTER_VALIDATE_EMAIL) !== false;
    }

    #[Rule(RuleEnum::MaxLength, 'field_length_larger')]
    public function maxLength(mixed $value, int $max): bool
    {
        if ($max <= 0) {
            throw new \InvalidArgumentException('Max cannot be less than or equal to zero');
        }

        return strlen($value) <= $max;
    }

    #[Rule(RuleEnum::MinLength, 'field_length_shorter')]
    public function minLength(mixed $value, int $min): bool
    {
        if ($min < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return strlen($value) >= $min;
    }

    #[Rule(RuleEnum::Min, 'field_value_smaller')]
    public function min(mixed $value, float|int $min): bool
    {
        if (!$this->numeric($value)) {
            return false;
        }

        if ($min < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return $value >= $min;
    }

    #[Rule(RuleEnum::Max, 'field_value_bigger')]
    public function max(mixed $value, float|int $max): bool
    {
        if (!$this->numeric($value)) {
            return false;
        }

        if ($max < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return $value <= $max;
    }

    #[Rule(RuleEnum::Enum, 'field_value_not_in_enum')]
    public function enum(mixed $value, array $enum, bool $strict = true): bool
    {
        return \in_array($value, $enum, $strict);
    }
}
