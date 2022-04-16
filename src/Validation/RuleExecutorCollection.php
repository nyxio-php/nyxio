<?php

declare(strict_types=1);

namespace Nyxio\Validation;

use Nyxio\Contract\Container\ContainerInterface;
use Nyxio\Contract\Validation\RuleExecutorCollectionInterface;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Validation\Attribute\Rule;
use Nyxio\Validation\Attribute\RuleGroup;

class RuleExecutorCollection implements RuleExecutorCollectionInterface
{
    /**
     * @var RuleExecutor[]
     */
    private array $executors = [];

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ExtractAttribute $extractAttribute
    ) {
    }

    public function register(string $class): static
    {
        try {
            $reflection = new \ReflectionClass($class);
            $this->container->singleton($class);

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $rule = $this->extractAttribute->first($method, Rule::class);

                if (!$rule instanceof Rule) {
                    continue;
                }

                $group = $this->extractAttribute->first($method->getDeclaringClass(), RuleGroup::class);

                $alias = $group instanceof RuleGroup ? \sprintf('%s.%s', $group->name, $rule->getShort()) : $rule->getShort();

                $this->executors[$alias] = new RuleExecutor(
                    $method,
                    $rule,
                    $this->container->get($class)
                );
            }
        } catch (\ReflectionException $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $this;
    }

    public function all(): array
    {
        return $this->executors;
    }

    public function has(string $alias): bool
    {
        return isset($this->executors[$alias]);
    }

    public function get(string $alias): ?RuleExecutor
    {
        return $this->executors[$alias] ?? null;
    }
}
