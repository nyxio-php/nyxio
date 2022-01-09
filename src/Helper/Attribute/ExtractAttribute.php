<?php

declare(strict_types=1);

namespace Nyxio\Helper\Attribute;

class ExtractAttribute
{
    public function first(
        \ReflectionClass|\ReflectionClassConstant|\ReflectionMethod|\ReflectionFunction|\ReflectionProperty|\ReflectionParameter|string $source,
        string $attributeName
    ): ?object {
        $source = $this->getReflection($source);

        $attributes = $source->getAttributes($attributeName);

        if (empty($attributes)) {
            return null;
        }

        return \array_shift($attributes)?->newInstance();
    }

    /**
     * @param \ReflectionClass|\ReflectionClassConstant|\ReflectionMethod|\ReflectionFunction|\ReflectionProperty|\ReflectionParameter|string $source
     * @param string $attributeName
     * @param bool $parent
     * @return object[]
     */
    public function all(
        \ReflectionClass|\ReflectionClassConstant|\ReflectionMethod|\ReflectionFunction|\ReflectionProperty|\ReflectionParameter|string $source,
        string $attributeName,
        bool $parent = false
    ): array {
        $source = $this->getReflection($source);

        if ($parent) {
            $parentSource = $this->getParentSource($source);

            if (!empty($parentSource)) {
                return array_merge(
                    $this->all($parentSource, $attributeName, parent: true),
                    $this->all($source, $attributeName)
                );
            }
        }

        return \array_map(
            static fn(\ReflectionAttribute $attribute) => $attribute->newInstance(),
            $source->getAttributes($attributeName)
        );
    }

    protected function getReflection($reflectionOrClass
    ): \ReflectionClass|\ReflectionClassConstant|\ReflectionMethod|\ReflectionFunction|\ReflectionProperty|\ReflectionParameter {
        if (\is_string($reflectionOrClass)) {
            try {
                return new \ReflectionClass($reflectionOrClass);
            } catch (\ReflectionException $exception) {
                throw new \InvalidArgumentException($exception->getMessage(), $exception->getCode(), $exception);
            }
        }

        return $reflectionOrClass;
    }

    private function getParentSource(mixed $source): mixed
    {
        $parentClass = match (\get_class($source)) {
            \ReflectionClass::class => $source->getParentClass(),
            \ReflectionMethod::class, \ReflectionProperty::class, \ReflectionClassConstant::class => $source
                ->getDeclaringClass()
                ->getParentClass(),
            default => null,
        };

        if (empty($parentClass)) {
            return null;
        }

        try {
            return match (\get_class($source)) {
                \ReflectionClass::class => $parentClass,
                \ReflectionMethod::class => $parentClass->getMethod($source->getName()),
                \ReflectionProperty::class => $parentClass->getProperty($source->getName()),
                \ReflectionClassConstant::class => $parentClass->getConstant($source->getName()),
                default => null,
            };
        } catch (\ReflectionException) {
            return null;
        }
    }
}
