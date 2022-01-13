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

        $attributeKeys = \explode('.', $validator->attribute);
        $valueExists = false;
        $mainKey = \array_shift($attributeKeys);
        $value = null;

        if (\array_key_exists($mainKey, $source)) {
            $value = $source[$mainKey];
            $valueExists = true;

            foreach ($attributeKeys as $key) {
                if (!\array_key_exists($key, $value)) {
                    $valueExists = false;

                    break;
                }

                $value = $value[$key];
            }
        }

        $defaultParams = [
            'source' => $source,
            'attribute' => $validator->attribute,
        ];

        if ($valueExists) {
            $defaultParams['value'] = $value;

            if ($value === null) {
                if ($validator->isNullable()) {
                    return [];
                }

                $errors[$validator->attribute][] = getFormattedText(
                    $validator->getNullableMessage(),
                    $defaultParams
                );

                return $errors;
            }

            if ($value === '' && !$validator->isAllowsEmpty()) {
                $errors[$validator->attribute][] = getFormattedText(
                    $validator->getAllowsEmptyMessage(),
                    $defaultParams
                );

                return $errors;
            }
        } elseif ($validator->isRequired()) {
            $errors[$validator->attribute][] = getFormattedText($validator->getRequiredMessage(), $defaultParams);

            return $errors;
        }

        foreach ($validator->getRules() as $rule => $ruleData) {
            $executor = $this->executorCollection->get($rule);

            if ($executor === null) {
                continue;
            }

            $validateParams = \array_merge(
                $defaultParams,
                $ruleData['params']
            );

            if ($executor->validate($validateParams)) {
                continue;
            }

            $errors[$validator->attribute][] = getFormattedText(
                $ruleData['message'] ?? $executor->rule->message,
                $validateParams
            );
        }

        return $errors;
    }
}
