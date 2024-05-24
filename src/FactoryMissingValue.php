<?php

namespace Ensi\LaravelTestFactories;

class FactoryMissingValue implements PotentiallyMissing
{
    /**
     * Determine if the object should be considered "missing".
     *
     * @return bool
     */
    public function isMissing()
    {
        return true;
    }
}
