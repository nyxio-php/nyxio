<?php

declare(strict_types=1);

namespace Nyxio\Contract\Validation\Handler;

use Nyxio\Http\Exception\HttpException;
use Nyxio\Validation\Handler\Field;

interface ValidatorCollectionInterface
{
    public function field(string $name): Field;

    /**
     * @param array $source
     * @return array
     */
    public function getErrors(array $source): array;

    /**
     * @param array $source
     * @return bool
     *
     * @throws HttpException
     */
    public function validateOrException(array $source): bool;
}
