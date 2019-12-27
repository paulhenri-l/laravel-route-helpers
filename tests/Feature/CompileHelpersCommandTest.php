<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Feature;

use PaulhenriL\LaravelRouteHelpers\Tests\TestCase;

class CompileHelpersCommandTest extends TestCase
{
    public function test_compilation()
    {
        $generatedFile = base_path(config('route_helpers.helpers_path'));

        if (file_exists($generatedFile)) {
            unlink($generatedFile);
        }

        $this->artisan('route:compile-helpers')
            ->expectsOutput('Helper file compiled');

        $helpersFilePath = $this->app->basePath(
            config('route_helpers.helpers_path')
        );

        $this->assertFileExists($helpersFilePath);
        $this->assertEquals(
            file_get_contents(__DIR__ . '/../Stubs/route_helpers.php'),
            file_get_contents($helpersFilePath)
        );
    }
}
