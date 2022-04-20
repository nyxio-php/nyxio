<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Cron\Attribute;

use Nyxio\Kernel\Server\Cron\Attribute\Cron;
use PHPUnit\Framework\TestCase;

class CronTest extends TestCase
{
    public function testBasic(): void
    {
        $attribute = new Cron('* * * * *');

        $this->assertEquals('* * * * *', $attribute->expression);
    }
}
