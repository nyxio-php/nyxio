<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job\Async\Schedule;

use Nyxio\Kernel\Server\Job\Async\Schedule\Attribute\Schedule;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testBasic(): void
    {
        $attribute = new Schedule('* * * * *');

        $this->assertEquals('* * * * *', $attribute->expression);
    }
}
