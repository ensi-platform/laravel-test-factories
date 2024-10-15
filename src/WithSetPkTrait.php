<?php

namespace Ensi\LaravelTestFactories;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;

trait WithSetPkTrait
{
    protected bool $hasValue = false;
    protected array $pkValues = [];

    protected function stateSetPk(mixed $state, array $keys, bool $isSequence = false): static
    {
        if ($isSequence) {
            $variables = [];

            foreach ($state as $sequenceState) {
                $variables[] = $this->getVariables($sequenceState, $keys);
            }

            if ($this->hasValue) {
                $newSequence = new SequenceWithSetPk(...$state);
                $newSequence->pkVariables = $variables;

                return $this->getSequenceState($newSequence)->state($newSequence);
            }

            return parent::state(new Sequence(...$state));
        }

        if (is_array($state)) {
            $variables = $this->getVariables($state, $keys);

            if ($this->hasValue) {
                return $this->setPk(...$variables)->state($state);
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

    protected function getSequenceState(SequenceWithSetPk $sequence): self
    {
        return parent::state(function () use ($sequence) {
            $values = $sequence->pkVariables[$sequence->index % $sequence->count] ?? [null, null];

            $statesSequenceCount = $this->getPkSequenceStates()->count();
            $isLastSequence = false;
            $count = 0;
            $isNewSequence = true;
            foreach ($this->pkValues as $sequenceValues) {
                $count++;

                // Skip all sequences that are applied after current one
                if ($sequenceValues == $sequence->pkVariables) {
                    $isNewSequence = false;
                    $isLastSequence = $statesSequenceCount == $count;

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
                $this->pkValues[] = $sequence->pkVariables;
                $isLastSequence = $statesSequenceCount - 1 == $count;
            }

            // If this isn't the last sequence to be applied, we don't want to generate values
            if (!$isLastSequence) {
                return [];
            }

            return $this->generatePk(...$values);
        });
    }

    protected function getPkSequenceStates(): Collection
    {
        return $this->states->filter(fn ($state) => $state::class === SequenceWithSetPk::class && count($state->pkVariables) > 0);
    }

    public function getPk(): array
    {
        return $this->getPkSequenceStates()->isNotEmpty() ? [] : $this->generatePk();
    }
}
