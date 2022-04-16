<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

use Nyxio\Contract\Http\HttpStatus;
use Nyxio\Contract\Validation\Handler\RulesCheckerInterface;
use Nyxio\Contract\Validation\Handler\ValidatorCollectionInterface;
use Nyxio\Http\Exception\HttpException;

class ValidatorCollection implements ValidatorCollectionInterface
{
    /**
     * @var Field[]
     */
    protected array $validators = [];

    public function __construct(private readonly RulesCheckerInterface $rulesChecker)
    {
    }

    public function field(string $name): Field
    {
        return $this->validators[] = new Field($name);
    }

    /**
     * @inheritDoc
     */
    public function getErrors(array $source): array
    {
        return \array_merge(
            ...\array_map(
                   fn(Field $validator) => $this->rulesChecker->check($source, $validator),
                   $this->validators
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
