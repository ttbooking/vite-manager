<?php

namespace TTBooking\ViteManager\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\ViteManager\Facades\Vite;
use TTBooking\ViteManager\ViteServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [ViteServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return ['Vite' => Vite::class];
    }
}
