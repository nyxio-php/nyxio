<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Await;

use Nyxio\Kernel\Server\Job\Await\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testBasic(): void
    {
        $options = new Options(2);
        $this->assertEquals(2, $options->getTimeout());
    }

    public function testDefault(): void
    {
        $options = new Options();
        $this->assertEquals(0.5, $options->getTimeout());
    }
}
