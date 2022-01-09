<?php

declare(strict_types=1);

namespace Nyxio\Validation;

use Nyxio\Validation\Attribute\Rule;

class DefaultRules
{
    #[Rule('string', 'Attribute is not string')]
    public function string(mixed $value): bool
    {
        return \is_string($value);
    }

    #[Rule('integer', 'Attribute is not integer')]
    public function integer(mixed $value): bool
    {
        return \is_int($value);
    }

    #[Rule('numeric', 'Attribute is not numeric')]
    public function numeric(mixed $value): bool
    {
        return \is_numeric($value);
    }

    #[Rule('float', 'Attribute is not float')]
    public function float(mixed $value): bool
    {
        return \is_float($value);
    }

    #[Rule('bool', 'Attribute is not boolean')]
    public function bool(mixed $value): bool
    {
        return \is_bool($value);
    }

    #[Rule('array', 'Attribute is not array')]
    public function array(mixed $value): bool
    {
        return \is_array($value);
    }

    #[Rule('email', 'Attribute is not email')]
    public function email(mixed $value): bool
    {
        return is_string(\filter_var($value, \FILTER_VALIDATE_EMAIL));
    }

    #[Rule('max-len', 'Attribute length larger :max')]
    public function maxLength(mixed $value, int $max): bool
    {
        if ($max <= 0) {
            throw new \InvalidArgumentException('Max cannot be less than or equal to zero');
        }

        return strlen($value) <= $max;
    }

    #[Rule('min-len', 'Attribute length shorter :min')]
    public function minLength(mixed $value, int $min): bool
    {
        if ($min < 0) {
            throw new \InvalidArgumentException('Min cannot be less than zero');
        }

        return strlen($value) >= $min;
    }
}
