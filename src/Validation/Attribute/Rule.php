<?php

declare(strict_types=1);

namespace Nyxio\Validation\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Rule
{
    private string $short;

    public function __construct(string|\BackedEnum $short, public readonly string $message)
    {
        $this->short = $short instanceof \BackedEnum ? $short->value : $short;
    }

    public function getShort(): string
    {
        return $this->short;
    }
}
