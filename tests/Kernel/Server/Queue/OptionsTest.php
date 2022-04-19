<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Queue;

use Nyxio\Kernel\Server\Queue\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testBasic(): void
    {
        $options = new Options(retryCount: 10, retryDelay: 1000, delay: 5);
        $this->assertEquals(10, $options->getRetryCount());
        $this->assertEquals(1000, $options->getRetryDelay());
        $this->assertEquals(5, $options->getDelay());
    }

    public function testException1(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Options(retryCount: 10, retryDelay: 1000, delay: -1);
    }

    public function testException2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Options(retryCount: 10, retryDelay: -1, delay: 100);
    }

    public function testException3(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Options(retryCount: -1, retryDelay: 10, delay: 100);
    }
}
