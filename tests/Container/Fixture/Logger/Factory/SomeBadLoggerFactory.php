<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture\Logger\Factory;

use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;

class SomeBadLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(public readonly LoggerInterface $logger, public readonly string $channel)
    {
    }
}
