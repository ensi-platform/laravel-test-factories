<?php

use Ensi\LaravelTestFactories\Tests\Stubs\TestObjectModel;
use Ensi\LaravelTestFactories\Tests\Stubs\TestObjectWithSetPkTraitFactory;

test('TestObjectWithSetPkTraitFactory create', function () {
    /** @var TestObjectModel $result */
    $result = TestObjectWithSetPkTraitFactory::new()->make(['amount' => 100]);

    expect($result->amount)->toEqual(100);
});

test('TestObjectWithSetPkTraitFactory create with setPk', function () {
    /** @var TestObjectModel $result */
    $result = TestObjectWithSetPkTraitFactory::new()->setPk(1)->make();

    expect($result->client_id)->toEqual(1);
});

test('TestObjectWithSetPkTraitFactory create with sequence', function () {
    $result = TestObjectWithSetPkTraitFactory::new()
        ->count(10)
        ->sequence(
            ['amount' => 100],
            ['amount' => 200],
        )
        ->make();

    expect($result->filter(fn (TestObjectModel $object) => $object->amount == 200)->count())->toEqual(5);
});

test('TestObjectWithSetPkTraitFactory create with sequence affecting PK', function () {
    $result = TestObjectWithSetPkTraitFactory::new()
        ->count(10)
        ->sequence(
            ['client_id' => 1],
            ['client_id' => 2],
        )
        ->make();

    expect($result->filter(fn (TestObjectModel $object) => $object->client_id == 2)->count())->toEqual(5);
});

test('TestObjectWithSetPkTraitFactory create with multiple sequences', function () {
    $result = TestObjectWithSetPkTraitFactory::new()
        ->count(10)
        ->sequence(
            ['client_id' => 1],
            ['client_id' => 2],
        )
        ->sequence(
            ['amount' => 100],
            ['amount' => 200],
            ['amount' => 300]
        )
        ->make();

    expect($result->filter(fn (TestObjectModel $object) => $object->client_id == 2)->count())->toEqual(5);
    expect($result->filter(fn (TestObjectModel $object) => $object->amount == 100)->count())->toEqual(4);
});

test('TestObjectWithSetPkTraitFactory create with multiple sequences affecting PK', function () {
    $result = TestObjectWithSetPkTraitFactory::new()
        ->count(10)
        ->sequence(
            ['client_id' => 1],
            ['client_id' => 2],
        )
        ->sequence(
            ['location_id' => 10],
            ['location_id' => 20],
            ['location_id' => 30],
            ['location_id' => 40],
            ['location_id' => 50]
        )
        ->make();

    expect($result->filter(fn (TestObjectModel $object) => $object->client_id == 2)->count())->toEqual(5);
    expect($result->filter(fn (TestObjectModel $object) => $object->location_id == 10)->count())->toEqual(2);
});

test('TestObjectWithSetPkTraitFactory create with multiple sequences affecting PK - reverse count', function () {
    $result = TestObjectWithSetPkTraitFactory::new()
        ->count(10)
        ->sequence(
            ['client_id' => 1],
            ['client_id' => 2],
            ['client_id' => 3],
            ['client_id' => 4],
            ['client_id' => 5],
        )
        ->sequence(
            ['location_id' => 10],
            ['location_id' => 20],
        )
        ->make();

    expect($result->filter(fn (TestObjectModel $object) => $object->client_id == 2)->count())->toEqual(2);
    expect($result->filter(fn (TestObjectModel $object) => $object->location_id == 10)->count())->toEqual(5);
});
