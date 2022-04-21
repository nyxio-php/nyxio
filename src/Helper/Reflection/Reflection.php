<?php

declare(strict_types=1);

namespace Nyxio\Helper\Reflection;

function getMethodParametersNames(\ReflectionMethod $method): array
{
    return \array_filter(
        array_merge(
            ...
            \array_map(
                static function (\ReflectionParameter $parameter) {
                    if ($parameter->getType() instanceof \ReflectionNamedType) {
                        return [$parameter->getName() => $parameter->getType()->getName()];
                    }

                    return [$parameter->getName() => null];
                },
                $method->getParameters()
            )
        )
    );
}
