<?php

declare(strict_types=1);

namespace Nyxio\Routing;

use Nyxio\Validation\Handler\Field;

trait ValidatorTrait
{
    /**
     * @var Field[]
     */
    private array $validators = [];

    public function appendValidators(array $validators): static
    {
        $this->validators = \array_merge($this->validators, $validators);

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    public function getValidator(string $param): ?Field
    {
        return $this->validators[$param] ?? null;
    }

    private function createValidators(array $data): void
    {
        foreach ($data as $name => $rules) {
            $rules = \is_array($rules) ? $rules : [$rules];
            $this->validators[$name] = new Field($name);

            foreach ($rules as $key => $value) {
                $withParams = \is_array($value);
                $this->validators[$name]->rule($withParams ? $key : $value, $withParams ? $value : []);
            }
        }
    }
}
