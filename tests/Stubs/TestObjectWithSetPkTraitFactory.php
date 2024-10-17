<?php

namespace Ensi\LaravelTestFactories\Tests\Stubs;

use Ensi\LaravelTestFactories\BaseModelFactory;
use Ensi\LaravelTestFactories\WithSetPkTrait;

class TestObjectWithSetPkTraitFactory extends BaseModelFactory
{
    use WithSetPkTrait;

    protected $model = TestObjectModel::class;

    public function definition(): array
    {
        return array_merge($this->getPk(), [
            'amount' => $this->faker->numberBetween(1, 1_000_000),
        ]);
    }

    public function getPkFields(): array
    {
        return ['client_id', 'location_id'];
    }

    public function setPk(?int $clientId = null, ?string $locationId = null): self
    {
        return $this->state(function () use ($clientId, $locationId) {
            return $this->generatePk($clientId, $locationId);
        });
    }

    protected function generatePk(?int $clientId = null, ?string $locationId = null): array
    {
        $clientIdFormat = $clientId ?: '\d{10}';
        $locationIdFormat = $locationId ?: '[0-9]{1,10}';

        $unique = $this->faker->unique()->regexify("/^{$clientIdFormat}_{$locationIdFormat}");

        $uniqueArr = explode('_', $unique);

        return [
            'client_id' => (int)$uniqueArr[0],
            'location_id' => $uniqueArr[1],
        ];
    }
}
