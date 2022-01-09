<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture\Logger;

class TextLogger implements LoggerInterface
{
    public function __construct(public string $message = 'test')
    {
    }
}
