<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Stubs;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Should we add extra routes to the router?
     *
     * @var bool
     */
    static $addExtraRoutes = false;

    public function boot()
    {
        $router = $this->app->make('router');

        $router->resource(
            'posts.comments', PostsCommentsController::class
        );

        if (static::$addExtraRoutes == true) {
            $router->resource(
                'posts', PostsController::class
            );
        }
    }
}
