<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Utility;

use Nyxio\Kernel\Utility\UuidFactory;
use PHPUnit\Framework\TestCase;

class UuidFactoryTest extends TestCase
{
    public function testBasic(): void
    {
        $factory = new UuidFactory();

        $this->assertEquals(36, \mb_strlen($factory->generate()));
    }
}
