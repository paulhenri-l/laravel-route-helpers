<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests;

use PaulhenriL\LaravelRouteHelpers\ServiceProvider;
use PaulhenriL\LaravelRouteHelpers\Tests\Stubs\TestServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Are Recompilation checks enabled?
     *
     * @var bool
     */
    protected static $recompilationChecksEnabled = false;

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
        return [
            TestServiceProvider::class,
            ServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->make('config')->set(
            'route_helpers.recompilation_checks_enabled',
            static::$recompilationChecksEnabled
        );
    }

    /**
     * Add the extra routes when booting the test app?
     */
    protected function withExtraRoutes()
    {
        TestServiceProvider::$addExtraRoutes = true;
    }

    /**
     * Remove the extra routes when booting the test app?
     */
    protected function withoutExtraRoutes()
    {
        TestServiceProvider::$addExtraRoutes = false;
    }

    /**
     * Enable recompilation checks in the config.
     */
    protected function enableRecompilationChecks()
    {
        static::$recompilationChecksEnabled = true;
    }

    /**
     * Disable recompilation checks in the config.
     */
    protected function disableRecompilationChecks()
    {
        static::$recompilationChecksEnabled = false;
    }

    /**
     * Run the callback and reboot the app.
     */
    protected function rebootApp(?callable $callback = null)
    {
        $callback ? $callback() : null;

        $this->tearDown();
        $this->setUp();
    }
}
