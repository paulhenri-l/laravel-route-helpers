<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Unit\Helpers;

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use PaulhenriL\LaravelRouteHelpers\Helpers\Generator;
use PaulhenriL\LaravelRouteHelpers\Tests\TestCase;

class GeneratorTest extends TestCase
{
    public function test_helper_functions_are_generated()
    {
        $router = new Router(new Dispatcher());

        $router->resource('posts.comments', 'HelloController');

        $generator = new Generator($router);

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../../Stubs/route_helpers.php'),
            $generator->generateHelpers()
        );
    }
}
