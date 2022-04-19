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
}
