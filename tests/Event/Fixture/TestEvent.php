<?php

declare(strict_types=1);

namespace Nyxio\Tests\Event\Fixture;

use Nyxio\Event\Event;

class TestEvent extends Event
{
    public const NAME = 'test.event';
}
