<?php

namespace Ensi\LaravelTestFactories;

use Illuminate\Database\Eloquent\Factories\Sequence;

class SequenceWithSetPk extends Sequence
{
    public array $pkVariables = [];
}
