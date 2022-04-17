<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation;

enum Rule: string
{
    case String = 'string';
    case Integer = 'integer';
    case Numeric = 'numeric';
    case Float = 'float';
    case Bool = 'bool';
    case Array = 'array';
    case Email = 'email';
    case MaxLength = 'max-len';
    case MinLength = 'min-len';
    case Max = 'max';
    case Min = 'min';
    case Between = 'between';
    case Equal = 'equal';
    case NotEqual = 'not-equal';
    case Enum = 'enum';
    case Contains = 'contains';
}
