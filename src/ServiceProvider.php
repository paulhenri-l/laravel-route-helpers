<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use PaulhenriL\LaravelRouteHelpers\Commands\RouteCompileHelpers;
use PaulhenriL\LaravelRouteHelpers\Helpers\Generator;
use PaulhenriL\LaravelRouteHelpers\Helpers\Loader;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $configPath = __DIR__ . '/../config/route_helpers.php',
            'route_helpers'
        );

        $this->publishes([
            $configPath => config_path('route_helpers.php')
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->booted(function () {
            $generator = new Generator($this->app->make('router'));

            $loader = new Loader(new Filesystem(), $generator, [
                'file_path' => base_path(config('route_helpers.helpers_path')),
                'recompilation_checks_enabled' => config('route_helpers.recompilation_checks_enabled')
            ]);

            $loader->load();
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                RouteCompileHelpers::class
            ]);
        }
    }
}
