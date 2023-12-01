<?php

namespace TTBooking\ViteManager\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\ViteManager\Facades\Vite;
use TTBooking\ViteManager\ViteServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ViteServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Vite' => Vite::class];
    }
}
