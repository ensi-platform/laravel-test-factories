<?php

namespace Ensi\LaravelTestFactories;

use Illuminate\Database\Eloquent\Factories\Sequence;

trait WithSetPkTrait
{
    protected bool $hasValue = false;
    protected array $pkValues = [];

    protected function stateSetPk(mixed $state, array $keys, bool $isSequence = false): static
    {
        if ($isSequence) {
            $newSequence = new Sequence(...$state);
            $variables = [];

            foreach ($state as $sequenceState) {
                $variables[] = $this->getVariables($sequenceState, $keys);
            }

            if ($this->hasValue) {
                return $this->getSequenceState($variables, $newSequence)->state($newSequence);
            }

            return parent::state($newSequence);
        }

        if (is_array($state)) {
            $variables = $this->getVariables($state, $keys);

            if ($this->hasValue) {
                return $this->state(function () use ($variables) {
                    return $this->generatePk(...$variables);
                })
                    ->state($state);
            }
        }

        return parent::state($state);
    }

    protected function getVariables(mixed &$state, array $keys): array
    {
        $variables = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $state)) {
                $variables[] = $state[$key];
                unset($state[$key]);
                $this->hasValue = true;
            } else {
                $variables[] = null;
            }
        }

        return $variables;
    }

    protected function getSequenceState(array $variables, Sequence $sequence): self
    {
        return $this->state(function () use ($variables, $sequence) {
            $values = $variables[$sequence->index % $sequence->count] ?? [null, null];

            $isNewSequence = true;
            foreach ($this->pkValues as $sequenceValues) {
                // Skip all sequences that are applied after current one
                if ($sequenceValues == $variables) {
                    $isNewSequence = false;

                    break;
                }

                // Apply previously created sequence to current one
                foreach ($sequenceValues[$sequence->index % count($sequenceValues)] as $pkKey => $pkValue) {
                    if ($values[$pkKey]) {
                        continue;
                    }

                    $values[$pkKey] = $pkValue;
                }
            }

            if ($isNewSequence) {
                $this->pkValues[] = $variables;
            }

            return $this->generatePk(...$values);
        });
    }
}
