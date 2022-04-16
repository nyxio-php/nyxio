<?php

declare(strict_types=1);

namespace Nyxio\Validation;

use Nyxio\Contract\Validation\Rule as RuleEnum;
use Nyxio\Validation\Attribute\Rule;

class DefaultRules
{
    #[Rule(RuleEnum::String, 'Attribute is not string')]
    public function string(mixed $value): bool
    {
        return \is_string($value);
    }

    #[Rule(RuleEnum::Integer, 'Attribute is not integer')]
    public function integer(mixed $value): bool
    {
        return \is_int($value);
    }

    #[Rule(RuleEnum::Numeric, 'Attribute is not numeric')]
    public function numeric(mixed $value): bool
    {
        return \is_numeric($value);
    }

    #[Rule(RuleEnum::Float, 'Attribute is not float')]
    public function float(mixed $value): bool
    {
        return \is_float($value);
    }

    #[Rule(RuleEnum::Bool, 'Attribute is not boolean')]
    public function bool(mixed $value): bool
    {
        return \is_bool($value);
    }

    #[Rule(RuleEnum::Array, 'Attribute is not array')]
    public function array(mixed $value): bool
    {
        return \is_array($value);
    }

    #[Rule(RuleEnum::Email, 'Attribute is not email')]
    public function email(mixed $value): bool
    {
        return \filter_var($value, \FILTER_VALIDATE_EMAIL) !== false;
    }

    #[Rule(RuleEnum::MaxLength, 'Attribute length larger :max')]
    public function maxLength(mixed $value, int $max): bool
    {
        if ($max <= 0) {
            throw new \InvalidArgumentException('Max cannot be less than or equal to zero');
        }

        return strlen($value) <= $max;
    }

    #[Rule(RuleEnum::MinLength, 'Attribute length shorter :min')]
    public function minLength(mixed $value, int $min): bool
    {
        if ($min < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return strlen($value) >= $min;
    }

    #[Rule(RuleEnum::Min, 'Value shorter :min')]
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

    #[Rule(RuleEnum::Max, 'Value larger :max')]
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

    #[Rule(RuleEnum::Enum, 'Invalid value')]
    public function enum(mixed $value, array $enum, bool $strict = true): bool
    {
        return \in_array($value, $enum, $strict);
    }
}
