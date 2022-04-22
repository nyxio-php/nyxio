<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Cron\Attribute;

use Nyxio\Kernel\Server\Job\Schedule\Attribute\Schedule;
use PHPUnit\Framework\TestCase;

class CronTest extends TestCase
{
    public function testBasic(): void
    {
        $attribute = new Schedule('* * * * *');

        $this->assertEquals('* * * * *', $attribute->expression);
    }
}
