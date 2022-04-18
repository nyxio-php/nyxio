<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation;

use Nyxio\Validation\DTO\RuleExecutor;

interface RuleExecutorCollectionInterface
{
    public function register(string $class): static;

    public function has(string $alias): bool;

    public function get(string $alias): ?RuleExecutor;
}
