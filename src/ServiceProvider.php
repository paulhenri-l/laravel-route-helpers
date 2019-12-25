<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->booted(function () {
            $generator = new HelpersGenerator($this->app->make('router'));

            $loader = new HelpersLoader(new Filesystem(), $generator, [
                'file_path' => $this->app->bootstrapPath('/cache/route_helpers.php')
            ]);

            $loader->load();
        });
    }
}
