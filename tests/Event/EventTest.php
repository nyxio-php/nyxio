<?php

declare(strict_types=1);

namespace Nyxio\Tests\Event;

use Nyxio\Event\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testBasic(): void
    {
        $event = new class () extends Event {

        };

        $this->assertNotEmpty($event::NAME);
        $this->assertFalse($event->isPropagationStopped());

        $event->stopPropagation();

        $this->assertTrue($event->isPropagationStopped());
    }
}
