<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests;

use PaulhenriL\LaravelRouteHelpers\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Your code here
    }

    /**
     * Register Package providers for testing.
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
