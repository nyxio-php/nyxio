<?php

declare(strict_types=1);

namespace Nyxio\Routing;

use Nyxio\Validation\Handler\Field;

trait ValidatorTrait
{
    /**
     * @var Field[]
     */
    private array $fields = [];

    public function appendFields(array $fields): static
    {
        $this->fields = \array_merge($this->fields, $fields);

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $param): ?Field
    {
        return $this->fields[$param] ?? null;
    }

    private function createFields(array $data): void
    {
        foreach ($data as $name => $rules) {
            $rules = \is_array($rules) ? $rules : [$rules];
            $this->fields[$name] = new Field($name);

            foreach ($rules as $key => $value) {
                $withParams = \is_array($value);
                $this->fields[$name]->rule($withParams ? $key : $value, $withParams ? $value : []);
            }
        }
    }
}
