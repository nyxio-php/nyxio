<?php

declare(strict_types=1);

namespace Nyxio\Contract\Container;

use Nyxio\Container\Injection;

interface ContainerInterface
{
    public function bind(string $name, string $className): Injection;

    public function singleton(string $name, object|string|null $classNameOrInstance = null): Injection;

    public function singletonFn(string $name, \Closure $closure): Injection;

    public function get(string $name, array $constructorParams = []): object;

    public function hasSingleton(string $name): bool;

    public function hasBind(string $name): bool;

    public function getMethodArguments(\ReflectionMethod $method, array $containerParams = []): array;
}
