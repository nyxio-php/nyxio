<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Request;

use Nyxio\Kernel\Request\ActionCache;

interface ActionCollectionInterface
{
    /**
     * @return ActionCache[]
     */
    public function all(): array;

    /**
     * @param string[] $actions
     * @return void
     * @throws \ReflectionException
     */
    public function create(array $actions): void;
}
