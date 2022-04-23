<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Async;

use Nyxio\Kernel\Server\Job\Async\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testBasic(): void
    {
        $function = static function () {

        };
        $options = new Options(retryCount: 10, retryDelay: 1000, delay: 5, finishCallback: $function);
        $this->assertEquals(10, $options->getRetryCount());
        $this->assertEquals(1000, $options->getRetryDelay());
        $this->assertEquals(5, $options->getDelay());
        $this->assertEquals(5, $options->getDelay());
        $this->assertEquals($function, $options->getFinishCallback());
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

    public function testDecreaseRetryCount(): void
    {
        $options = new Options(retryCount: 2);
        $this->assertEquals(2, $options->getRetryCount());
        $options->decreaseRetryCount();
        $this->assertEquals(1, $options->getRetryCount());
        $options->decreaseRetryCount();
        $this->assertEquals(0, $options->getRetryCount());
        $options->decreaseRetryCount();
        $this->assertEquals(0, $options->getRetryCount());
    }

    public function testDecreaseRetryCount2(): void
    {
        $options = new Options(retryCount: 0);
        $this->assertEquals(0, $options->getRetryCount());
        $options->decreaseRetryCount();
        $this->assertEquals(0, $options->getRetryCount());
    }

    public function testDecreaseRetryCount3(): void
    {
        $options = new Options();
        $this->assertEquals(null, $options->getRetryCount());
        $options->decreaseRetryCount();
        $this->assertEquals(null, $options->getRetryCount());
    }
}
