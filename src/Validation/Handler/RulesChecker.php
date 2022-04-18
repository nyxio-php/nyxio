<?php

declare(strict_types=1);

namespace Nyxio\Validation\Handler;

use Nyxio\Contract\Kernel\Text\MessageInterface;
use Nyxio\Contract\Validation\Handler\RulesCheckerInterface;
use Nyxio\Contract\Validation\RuleExecutorCollectionInterface;

class RulesChecker implements RulesCheckerInterface
{
    public function __construct(
        private readonly RuleExecutorCollectionInterface $executorCollection,
        private readonly MessageInterface $message,
    ) {
    }

    public function check(array $source, Field $field): array
    {
        $errors = [];

        $fieldKeys = \explode('.', $field->name);
        $valueExists = false;
        $mainKey = \array_shift($fieldKeys);
        $value = null;

        if (\array_key_exists($mainKey, $source)) {
            $value = $source[$mainKey];
            $valueExists = true;

            foreach ($fieldKeys as $key) {
                if (!\array_key_exists($key, $value)) {
                    $valueExists = false;

                    break;
                }

                $value = $value[$key];
            }
        }

        $defaultParams = [
            'source' => $source,
            'field' => $field->name,
        ];

        if ($valueExists) {
            $defaultParams['value'] = $value;

            if ($value === null) {
                if ($field->isNullable()) {
                    return [];
                }

                $errors[$field->name][] = $this->message->text(
                    $field->getNullableMessage(),
                    $defaultParams
                );

                return $errors;
            }

            if ($value === '' && !$field->isAllowsEmpty()) {
                $errors[$field->name][] = $this->message->text(
                    $field->getAllowsEmptyMessage(),
                    $defaultParams
                );

                return $errors;
            }
        } elseif ($field->isRequired()) {
            $errors[$field->name][] = $this->message->text($field->getRequiredMessage(), $defaultParams);

            return $errors;
        }

        foreach ($field->getRules() as $rule => $ruleData) {
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

            $errors[$field->name][] = $this->message->text(
                $ruleData['message'] ?? $executor->rule->message,
                $validateParams
            );
        }

        foreach ($field->getCustomRules() as $customRule) {
            if ($customRule['validator']($defaultParams['value'])) {
                continue;
            }

            $errors[$field->name][] = $this->message->text($customRule['message'], $defaultParams);
        }

        return $errors;
    }
}
