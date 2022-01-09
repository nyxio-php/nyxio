<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture\Logger\Factory;

use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;

class SomeGoodLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(public readonly LoggerInterface $logger)
    {
    }
}
