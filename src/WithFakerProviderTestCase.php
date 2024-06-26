<?php

namespace Ensi\LaravelTestFactories;

trait WithFakerProviderTestCase
{
    protected function setUpWithFakerProviderTestCase(): void
    {
        FakerProvider::$optionalAlways = null;
    }
}
