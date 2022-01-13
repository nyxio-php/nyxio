<?php

declare(strict_types=1);

namespace Nyxio\Routing;

use Nyxio\Contract\Routing\GroupCollectionInterface;

class GroupCollection implements GroupCollectionInterface
{
    /**
     * @var Group[]
     */
    private array $groups = [];

    public function register(Group $group): static
    {
        $this->groups[$group->name] = $group;

        return $this;
    }

    public function get(string $name): ?Group
    {
        return $this->groups[$name] ?? null;
    }
}
