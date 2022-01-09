<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

use Nyxio\Contract\Validation\Handler\RulesCheckerInterface;
use Nyxio\Contract\Validation\RuleExecutorCollectionInterface;

use function Nyxio\Helper\Text\getFormattedText;

class RulesChecker implements RulesCheckerInterface
{
    public function __construct(private readonly RuleExecutorCollectionInterface $executorCollection)
    {
    }

    public function check(array $source, Validator $validator): array
    {
        $errors = [];

        $defaultParams = [
            'source' => $source,
            'attribute' => $validator->attribute,
        ];

        $valueExists = \array_key_exists($validator->attribute, $source);

        if ($valueExists) {
            $defaultParams['value'] = $source[$validator->attribute];
        }

        if ($valueExists && $source[$validator->attribute] === null && $validator->isNullable()) {
            return [];
        }

        if (empty($source[$validator->attribute]) && !$validator->isAllowsEmpty()) {
            $errors[$validator->attribute][] = getFormattedText($validator->getEmptyMessage(), $defaultParams);

            return $errors;
        }

        if ($valueExists && $source[$validator->attribute] === null && $validator->isNullable() === false) {
            $errors[$validator->attribute][] = getFormattedText($validator->getNullableMessage(), $defaultParams);

            return $errors;
        }

        foreach ($validator->getRules() as $rule => $parameters) {
            $executor = $this->executorCollection->get($rule);

            if ($executor === null) {
                continue;
            }

            $validateParams = \array_merge(
                $defaultParams,
                $parameters
            );

            if ($executor->validate($validateParams)) {
                continue;
            }

            $errors[$validator->attribute][] = getFormattedText($executor->rule->message, $validateParams);
        }

        return $errors;
    }
}
