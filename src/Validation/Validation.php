<?php

declare(strict_types=1);

namespace Nyxio\Validation;

use Nyxio\Contract\Http\HttpStatus;
use Nyxio\Contract\Validation\RulesCheckerInterface;
use Nyxio\Contract\Validation\ValidationInterface;
use Nyxio\Http\Exception\HttpException;
use Nyxio\Validation\DTO\Field;

class Validation implements ValidationInterface
{
    /**
     * @var Field[]
     */
    protected array $fields = [];

    public function __construct(private readonly RulesCheckerInterface $rulesChecker)
    {
    }

    public function field(string $name): Field
    {
        return $this->fields[] = new Field($name);
    }

    /**
     * @inheritDoc
     */
    public function getErrors(array $source): array
    {
        return \array_merge(
            ...\array_map(
                   fn(Field $validator) => $this->rulesChecker->check($source, $validator),
                   $this->fields
               )
        );
    }

    /**
     * @inheritDoc
     */
    public function validateOrException(array $source): bool
    {
        if (!empty($errors = $this->getErrors($source))) {
            throw new HttpException(HttpStatus::BadRequest, 'Bad Request', $errors);
        }

        return true;
    }
}
