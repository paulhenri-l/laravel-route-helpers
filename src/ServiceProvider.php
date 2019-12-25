<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $generator = new HelpersGenerator($this->app->make('router'));

        $loader = new HelpersLoader(new Filesystem(), $generator, [
            'file_path' => $this->app->bootstrapPath('/cache/route_helpers.php')
        ]);

        $loader->load();
    }
}
