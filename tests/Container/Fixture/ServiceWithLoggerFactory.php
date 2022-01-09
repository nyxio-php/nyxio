<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container\Fixture;

use Nyxio\Tests\Container\Fixture\Logger\Factory\LoggerFactoryInterface;

class ServiceWithLoggerFactory
{
    public function __construct(public readonly LoggerFactoryInterface $loggerFactory)
    {
    }
}
