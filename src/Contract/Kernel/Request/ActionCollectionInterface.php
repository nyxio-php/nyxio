<?php

declare(strict_types=1);

namespace Nyxio\Contract\Kernel\Request;

use Nyxio\Kernel\Request\Action;

interface ActionCollectionInterface
{
    /**
     * @return Action[]
     */
    public function all(): array;

    /**
     * @param string[] $actions
     * @return void
     * @throws \ReflectionException
     */
    public function create(array $actions): void;
}
