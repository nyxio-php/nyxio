<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

use Nyxio\Contract\Http\HttpStatus;
use Nyxio\Contract\Validation\Handler\RulesCheckerInterface;
use Nyxio\Contract\Validation\Handler\ValidationInterface;
use Nyxio\Http\Exception\HttpException;

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
