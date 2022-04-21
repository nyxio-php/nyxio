<?php

declare(strict_types=1);

namespace Nyxio\Validation\DTO;

use Nyxio\Validation\Attribute\Rule;

class RuleExecutor
{
    public function __construct(
        public readonly \ReflectionMethod $method,
        public readonly Rule $rule,
        public readonly object $class
    ) {
    }

    public function validate(array $arguments = []): bool
    {
        try {
            return $this->method->invokeArgs(
                $this->class,
                \array_intersect_key(
                    $arguments,
                    \array_merge(
                        ...
                        array_map(
                            static fn(\ReflectionParameter $param) => [$param->getName() => true],
                            $this->method->getParameters()
                        )
                    )
                )
            );
        } catch (\Throwable) {
            return false;
        }
    }
}
