<?php

declare(strict_types=1);

namespace Nyxio\Tests\Container;

use Nyxio\Container\Container;
use Nyxio\Tests\Container\Fixture\Logger\LoggerInterface;
use Nyxio\Tests\Container\Fixture\Logger\TextLogger;
use PHPUnit\Framework\TestCase;

class BindTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testBind(): void
    {
        $container = new Container();
        $container->bind(LoggerInterface::class, TextLogger::class);

        /** @var TextLogger $instance */
        $instance1 = $container->get(LoggerInterface::class);
        $instance1->message = 'first';

        /** @var TextLogger $instance2 */
        $instance2 = $container->get(LoggerInterface::class);

        $this->assertEquals('first', $instance1->message);
        $this->assertEquals('test', $instance2->message);
    }
}
