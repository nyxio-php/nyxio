<?php

declare(strict_types=1);

namespace Nyxio\Container;

use Nyxio\Contract\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var Injection[]
     */
    private array $singletons = [];

    /**
     * @var Injection[]
     */
    private array $binds = [];

    public function bind(string $name, string $className): Injection
    {
        return $this->binds[$name] = new Injection($name, $className);
    }

    public function singleton(string $name, object|string|null $classNameOrInstance = null): Injection
    {
        return $this->singletons[$name] = new Injection($name, $classNameOrInstance ?? $name);
    }

    public function singletonFn(string $name, \Closure $closure): Injection
    {
        return $this->singleton($name, $closure());
    }

    /**
     * @param string $name
     * @param array $constructorParams
     * @return object
     * @throws \ReflectionException
     */
    public function get(string $name, array $constructorParams = []): object
    {
        return $this->resolve($name, $constructorParams);
    }

    public function hasSingleton(string $name): bool
    {
        return isset($this->singletons[$name]);
    }

    public function hasBind(string $name): bool
    {
        return isset($this->binds[$name]);
    }

    /**
     * @param \ReflectionMethod $method
     * @param array $containerParams
     * @param array $additionalParams
     * @return array
     * @throws \ReflectionException
     */
    public function getMethodArguments(
        \ReflectionMethod $method,
        array $containerParams = [],
        array $additionalParams = []
    ): array {
        $arguments = [];

        foreach ($method->getParameters() as $parameter) {
            if (\array_key_exists($parameter->getName(), $additionalParams)) {
                $arguments[$parameter->getName()] = $additionalParams[$parameter->getName()];
                continue;
            }

            if (\array_key_exists($parameter->getName(), $containerParams)) {
                $arguments[$parameter->getName()] = $containerParams[$parameter->getName()];
                continue;
            }

            $arguments[$parameter->getName()] = $this->getValueFromParameter($method, $parameter);
        }

        return $arguments;
    }

    /**
     * @param \ReflectionMethod $method
     * @param \ReflectionParameter $parameter
     * @return mixed|void
     * @throws \ReflectionException
     */
    private function getValueFromParameter(\ReflectionMethod $method, \ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        $type = $parameter->getType();

        if ($type instanceof \ReflectionUnionType) {
            throw new \ReflectionException(
                \sprintf(
                    'Argument $%s in %s:%s can\'t be set: union parameter without default value: %s',
                    $parameter->getName(),
                    $method->getDeclaringClass()->getName(),
                    $method->getName(),
                    \implode(
                        '|',
                        \array_map(static fn(\ReflectionNamedType $type) => $type->getName(), $type->getTypes())
                    ),
                )
            );
        }

        if ($type->isBuiltin()) {
            throw new \ReflectionException(
                \sprintf(
                    'Property $%s has no default value (%s::%s)',
                    $parameter->getName(),
                    $method->getDeclaringClass()->getName(),
                    $method->getName(),
                )
            );
        }

        return $this->resolve($type->getName());
    }

    /**
     * @throws \ReflectionException
     */
    private function resolve(string $name, array $constructorParams = []): object
    {
        if ($name === ContainerInterface::class) {
            return $this;
        }

        $hasSingleton = $this->hasSingleton($name);
        if ($hasSingleton && $this->singletons[$name]->hasInstance() === true) {
            return $this->singletons[$name]->getInstance();
        }

        try {
            $instance = $this->getInstance($name, $constructorParams);
        } catch (\Throwable $exception) {
            throw new \ReflectionException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if ($hasSingleton && $this->singletons[$name]->hasInstance() === false) {
            $this->singletons[$name]->addInstance($instance);
        }

        return $instance;
    }

    /**
     * @param string $name
     * @param array $constructorParams
     * @return object
     * @throws \ReflectionException
     */
    private function getInstance(string $name, array $constructorParams = []): object
    {
        $instanceClassName = $name;
        $arguments = [];
        $hasBind = $this->hasBind($name);
        $hasSingleton = $this->hasSingleton($name);

        if ($hasBind) {
            $arguments = $this->binds[$name]->getArguments();
            $instanceClassName = $this->binds[$name]->classNameOrInstance;
        }

        if ($hasSingleton) {
            $instanceClassName = $this->singletons[$name]->classNameOrInstance;
            $arguments = $this->singletons[$name]->getArguments();
        }

        $reflection = new \ReflectionClass($instanceClassName);

        if (!$reflection->isInstantiable()) {
            throw new \ReflectionException(
                \sprintf('Class %s is not instantiable', $instanceClassName)
            );
        }

        /** @var \ReflectionMethod $constructor */
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstanceWithoutConstructor();
        }

        return $reflection->newInstanceArgs(
            $this->getMethodArguments(
                $constructor,
                $arguments,
                $constructorParams,
            )
        );
    }
}
