<?php

declare(strict_types=1);

namespace Nyxio\Contract\Routing;

use Nyxio\Routing\Group;

interface GroupCollectionInterface
{
    public function register(Group $group): static;

    public function get(string $name): ?Group;
}
