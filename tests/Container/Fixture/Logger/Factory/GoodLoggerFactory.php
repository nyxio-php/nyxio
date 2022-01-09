<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture\Logger\Factory;

use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;
use Nyxio\Tests\Container\Fixture\Logger\NullLogger;

class GoodLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(
        public LoggerInterface $logger = new NullLogger(),
        public readonly string $channel = 'application'
    ) {
    }
}
