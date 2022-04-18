<?php

declare(strict_types=1);

namespace Nyxio\Contract\Helper;

interface DateFormat
{
    public const DATE_TIME = \DateTimeInterface::ATOM;
    public const DATE = 'Y-m-d';
    public const TIME = '\TH:s:iP';
}
