<?php

declare(strict_types=1);

namespace Nyxio\Validation\Helper;

use Nyxio\Contract\Helper\DateFormat;
use Nyxio\Contract\Validation\Rule as RuleEnum;
use Nyxio\Validation\Attribute\Rule;

class DefaultRules
{
    #[Rule(RuleEnum::String, 'validation.field_is_not_string')]
    public function string(mixed $value): bool
    {
        return \is_string($value);
    }

    #[Rule(RuleEnum::Integer, 'validation.field_is_not_int')]
    public function integer(mixed $value): bool
    {
        return \is_int($value);
    }

    #[Rule(RuleEnum::Numeric, 'validation.field_is_not_numeric')]
    public function numeric(mixed $value): bool
    {
        return \is_numeric($value);
    }

    #[Rule(RuleEnum::Float, 'validation.field_is_not_float')]
    public function float(mixed $value): bool
    {
        return \is_float($value);
    }

    #[Rule(RuleEnum::Bool, 'validation.field_is_not_boolean')]
    public function bool(mixed $value): bool
    {
        return \is_bool($value);
    }

    #[Rule(RuleEnum::Array, 'validation.field_is_not_array')]
    public function array(mixed $value): bool
    {
        return \is_array($value);
    }

    #[Rule(RuleEnum::Email, 'validation.field_is_not_email')]
    public function email(mixed $value): bool
    {
        return \filter_var($value, \FILTER_VALIDATE_EMAIL) !== false;
    }

    #[Rule(RuleEnum::Url, 'validation.field_is_not_url')]
    public function url(mixed $value): bool
    {
        return \filter_var($value, \FILTER_VALIDATE_URL) !== false;
    }

    #[Rule(RuleEnum::MaxLength, 'validation.field_length_larger')]
    public function maxLength(mixed $value, int $max): bool
    {
        if ($max <= 0) {
            throw new \InvalidArgumentException('Max cannot be less than or equal to zero');
        }

        return \mb_strlen($value) <= $max;
    }

    #[Rule(RuleEnum::MinLength, 'validation.field_length_shorter')]
    public function minLength(mixed $value, int $min): bool
    {
        if ($min < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return \mb_strlen($value) >= $min;
    }

    #[Rule(RuleEnum::Min, 'validation.field_value_smaller')]
    public function min(mixed $value, float|int $min): bool
    {
        if (!$this->numeric($value)) {
            return false;
        }

        return $value >= $min;
    }

    #[Rule(RuleEnum::Max, 'validation.field_value_bigger')]
    public function max(mixed $value, float|int $max): bool
    {
        if (!$this->numeric($value)) {
            return false;
        }

        return $value <= $max;
    }

    #[Rule(RuleEnum::Between, 'validation.field_value_not_between')]
    public function between(mixed $value, float|int $from, float|int $to): bool
    {
        if (!$this->numeric($value)) {
            return false;
        }

        return $value >= $from && $value <= $to;
    }

    #[Rule(RuleEnum::Enum, 'validation.field_value_not_in_enum')]
    public function enum(mixed $value, array $enum, bool $strict = true): bool
    {
        return \in_array($value, $enum, $strict);
    }

    #[Rule(RuleEnum::Exclude, 'validation.field_value_excluded')]
    public function exclude(mixed $value, array $enum, bool $strict = true): bool
    {
        return !$this->enum($value, $enum, $strict);
    }

    #[Rule(RuleEnum::Equal, 'validation.field_not_equal')]
    public function equal(mixed $value, mixed $equal, bool $strict = true, bool $caseSensitive = true): bool
    {
        if (\is_string($value) && \is_string($equal) && !$caseSensitive) {
            $value = \mb_strtolower($value);
            $equal = \mb_strtolower($equal);
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        return $strict ? $value === $equal : $value == $equal;
    }

    #[Rule(RuleEnum::NotEqual, 'validation.field_cannot_be_equal')]
    public function notEqual(mixed $value, mixed $equal, bool $strict = true, bool $caseSensitive = true): bool
    {
        return !$this->equal($value, $equal, $strict, $caseSensitive);
    }

    #[Rule(RuleEnum::RegEx, 'validation.field_does_not_match_expression')]
    public function regEx(mixed $value, string $pattern): bool
    {
        return \preg_match($pattern, $value) === 1;
    }

    #[Rule(RuleEnum::DateTime, 'validation.field_is_not_a_date_time')]
    public function dateTime(mixed $value, string $format = DateFormat::DATE_TIME): bool
    {
        $dateTime = \DateTimeImmutable::createFromFormat($format, $value);

        return $dateTime !== false && $dateTime->format($format) === $value;
    }

    #[Rule(RuleEnum::Date, 'validation.field_is_not_a_date')]
    public function date(mixed $value, string $format = DateFormat::DATE): bool
    {
        $date = \DateTimeImmutable::createFromFormat($format, $value);

        return $date !== false && $date->format($format) === $value;
    }

    #[Rule(RuleEnum::Time, 'validation.field_is_not_a_time')]
    public function time(mixed $value, string $format = DateFormat::TIME): bool
    {
        $time = \DateTimeImmutable::createFromFormat($format, $value);

        return $time !== false && $time->format($format) === $value;
    }
}
