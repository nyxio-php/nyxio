<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request;

use Nyxio\Container\Container;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Kernel\Request\Action;
use Nyxio\Kernel\Request\ActionCollection;
use Nyxio\Routing\Group;
use Nyxio\Routing\GroupCollection;
use Nyxio\Tests\Kernel\Request\Fixture\ActionWithInvalidGroup;
use Nyxio\Tests\Kernel\Request\Fixture\InvalidAction;
use Nyxio\Tests\Kernel\Request\Fixture\TestAction;
use PHPUnit\Framework\TestCase;

class ActionCollectionTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testBasic(): void
    {
        $groupCollection = new GroupCollection();
        $groupCollection->register(
            new Group(
                name:   'version1', prefix: '/v1',
                parent: new Group(
                            name:   'api', prefix: '/api',
                            parent: new Group(
                                        name:   'beta',
                                        prefix: '/beta'
                                    )
                        )
            )
        );
        $collection = new ActionCollection(new Container(), new ExtractAttribute(), $groupCollection);
        $collection->create([TestAction::class]);

        $this->assertEquals([TestAction::class], \array_keys($collection->all()));

        $actionCache = $collection->all()[TestAction::class];

        $this->assertInstanceOf(Action::class, $actionCache);
        $this->assertEquals('/beta/api/v1/user/@id', $actionCache->route->getUri());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidAction(): void
    {
        $collection = new ActionCollection(new Container(), new ExtractAttribute(), new GroupCollection());
        $collection->create([InvalidAction::class]);

        $this->assertEmpty($collection->all());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testActionWithInvalidGroup(): void
    {
        $collection = new ActionCollection(new Container(), new ExtractAttribute(), new GroupCollection());
        $collection->create([ActionWithInvalidGroup::class]);

        $this->assertEquals([ActionWithInvalidGroup::class], \array_keys($collection->all()));
    }
}
