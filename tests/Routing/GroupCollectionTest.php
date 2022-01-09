<?php

declare(strict_types=1);

namespace Nyxio\Tests\Routing;

use Nyxio\Routing\Group;
use Nyxio\Routing\GroupCollection;
use PHPUnit\Framework\TestCase;

class GroupCollectionTest extends TestCase
{
    public function testBasic(): void
    {
        $collection = new GroupCollection();
        $group = new Group('test-group', '/prefix');

        $collection->register($group);

        $groupFromCollection = $collection->get('test-group');

        $this->assertInstanceOf(Group::class, $groupFromCollection);
        $this->assertEquals('test-group', $groupFromCollection->name);
        $this->assertEquals('/prefix', $groupFromCollection->prefix);

        $this->assertNull($collection->get('random-group'));
    }
}
