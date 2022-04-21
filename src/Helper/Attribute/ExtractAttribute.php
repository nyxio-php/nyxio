<?php

declare(strict_types=1);

namespace Nyxio\Helper\Attribute;

class ExtractAttribute
{
    private const ALLOWED_REFLECTION_CLASSES = [
        \ReflectionClass::class,
        \ReflectionClassConstant::class,
        \ReflectionMethod::class,
        \ReflectionFunction::class,
        \ReflectionProperty::class,
        \ReflectionParameter::class,
    ];

    public function first(mixed $source, string $attributeName): ?object
    {
        $source = $this->getReflection($source);

        $attributes = $source->getAttributes($attributeName);

        if (empty($attributes)) {
            return null;
        }

        return \array_shift($attributes)?->newInstance();
    }

    /**
     * @param mixed $source
     * @param string $attributeName
     * @param bool $parent
     * @return object[]
     */
    public function all(
        mixed $source,
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

    protected function getReflection($reflectionOrClass): mixed
    {
        if (\is_object($reflectionOrClass)) {
            if (\in_array(\get_class($reflectionOrClass), static::ALLOWED_REFLECTION_CLASSES)) {
                return $reflectionOrClass;
            }
        } elseif (\is_string($reflectionOrClass)) {
            try {
                return new \ReflectionClass($reflectionOrClass);
            } catch (\ReflectionException $exception) {
                throw new \InvalidArgumentException(
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception
                );
            }
        }

        throw new \InvalidArgumentException(
            \sprintf('Invalid $reflectionOrClass argument: %s', \get_class($reflectionOrClass))
        );
    }

    private function getParentSource(mixed $source): mixed
    {
        $parentClass = match (\get_class($source)) {
            \ReflectionClass::class => $source->getParentClass(),
            \ReflectionMethod::class,
            \ReflectionProperty::class,
            \ReflectionClassConstant::class => $source
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
