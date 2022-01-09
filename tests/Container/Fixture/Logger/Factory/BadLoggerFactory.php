<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture\Logger\Factory;

use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;

class BadLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(public readonly LoggerInterface|string $logger)
    {
    }
}
