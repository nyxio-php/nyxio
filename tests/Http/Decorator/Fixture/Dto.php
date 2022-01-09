<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Decorator\Fixture;

class Dto implements \JsonSerializable
{
    public bool $test = true;

    public function jsonSerialize(): mixed
    {
        throw new \Exception('asdf');
    }
}
