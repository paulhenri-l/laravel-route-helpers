<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Feature;

use PaulhenriL\LaravelRouteHelpers\Tests\TestCase;

class AutomaticHelpersGenertionTest extends TestCase
{
    public function test_helpers_are_automatically_created_if_they_dont_exists()
    {
        $this->rebootApp(function () {
            $generatedFile = base_path(config('route_helpers.helpers_path'));

            if (file_exists($generatedFile)) {
                unlink($generatedFile);
            }
        });

        $helpersFilePath = $this->app->basePath(
            config('route_helpers.helpers_path')
        );

        $this->assertFileExists($helpersFilePath);
        $this->assertEquals(
            file_get_contents(__DIR__ . '/../Stubs/route_helpers.php'),
            file_get_contents($helpersFilePath)
        );
    }

    public function test_helpers_are_recompiled_if_they_are_outdated()
    {
        $helpersFilePath = $this->app->basePath(
            config('route_helpers.helpers_path')
        );

        $this->rebootApp(function () {
            $generatedFile = base_path(config('route_helpers.helpers_path'));

            if (file_exists($generatedFile)) {
                unlink($generatedFile);
            }
        });

        // Retieve current version
        $originalHelpersFile = file_get_contents($helpersFilePath);

        // Add new routes
        $this->withExtraRoutes();
        $this->enableRecompilationChecks();
        $this->rebootApp();

        // Make sure it has been updated
        $this->assertNotEquals(
            file_get_contents($helpersFilePath),
            $originalHelpersFile
        );

        $this->withoutExtraRoutes();
        $this->disableRecompilationChecks();
    }

    public function test_helpers_are_not_recompiled_if_feature_is_not_enabled()
    {
        $helpersFilePath = $this->app->basePath(
            config('route_helpers.helpers_path')
        );

        $this->rebootApp(function () {
            $generatedFile = base_path(config('route_helpers.helpers_path'));

            if (file_exists($generatedFile)) {
                unlink($generatedFile);
            }
        });

        // Retieve current version
        $originalHelpersFile = file_get_contents($helpersFilePath);

        // Add new routes
        $this->withExtraRoutes();
        $this->rebootApp();

        // Make sure it has not been updated
        $this->assertEquals(
            file_get_contents($helpersFilePath),
            $originalHelpersFile
        );

        $this->withoutExtraRoutes();
    }
}
