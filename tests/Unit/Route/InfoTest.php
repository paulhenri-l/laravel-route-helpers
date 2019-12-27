<?php

namespace PaulhenriL\LaravelRouteHelpers\Tests\Unit\Info;

use Illuminate\Routing\Route;
use PaulhenriL\LaravelRouteHelpers\Exceptions\NonRestfulRouteException;
use PaulhenriL\LaravelRouteHelpers\Route\Info;
use PaulhenriL\LaravelRouteHelpers\Tests\TestCase;

class InfoTest extends TestCase
{
    public function test_it_can_return_the_route_name()
    {
        $route = (new Route(['GET'], '/test', []))
            ->name('hello.index');

        $routeInfo = new Info($route);

        $this->assertEquals('hello.index', $routeInfo->getRouteName());
    }

    public function test_it_knows_if_a_route_is_restful()
    {
        $route = (new Route(['GET'], '/test', []))
            ->name('hello.index');

        $routeInfo = new Info($route);

        $this->assertTrue($routeInfo->isRestful());
    }

    public function test_it_knows_if_a_route_is_not_restful()
    {
        $route = (new Route(['GET'], '/test', []))
            ->name('hello.toto');

        $routeInfo = new Info($route);

        $this->assertFalse($routeInfo->isRestful());
    }

    public function test_it_can_infer_the_helper_basename()
    {
        $indexRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.index')
        );

        $createRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.create')
        );

        $storeRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.store')
        );

        $showRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.show')
        );

        $editRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.edit')
        );

        $updateRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.update')
        );

        $destroyRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.destroy')
        );

        $this->assertEquals('posts', $indexRoute->getHelperBaseName());
        $this->assertEquals('newPost', $createRoute->getHelperBaseName());
        $this->assertEquals('posts', $storeRoute->getHelperBaseName());
        $this->assertEquals('post', $showRoute->getHelperBaseName());
        $this->assertEquals('editPost', $editRoute->getHelperBaseName());
        $this->assertEquals('post', $updateRoute->getHelperBaseName());
        $this->assertEquals('post', $destroyRoute->getHelperBaseName());
    }

    public function test_inference_works_with_nested_resources()
    {
        $indexRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.index')
        );

        $createRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.create')
        );

        $storeRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.store')
        );

        $showRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.show')
        );

        $editRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.edit')
        );

        $updateRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.update')
        );

        $destroyRoute = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.destroy')
        );

        $this->assertEquals('postComments', $indexRoute->getHelperBaseName());
        $this->assertEquals('newPostComment', $createRoute->getHelperBaseName());
        $this->assertEquals('postComments', $storeRoute->getHelperBaseName());
        $this->assertEquals('postComment', $showRoute->getHelperBaseName());
        $this->assertEquals('editPostComment', $editRoute->getHelperBaseName());
        $this->assertEquals('postComment', $updateRoute->getHelperBaseName());
        $this->assertEquals('postComment', $destroyRoute->getHelperBaseName());
    }

    public function test_helper_name_on_non_restful_route()
    {
        $this->expectException(NonRestfulRouteException::class);

        $routeInfo = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.hello')
        );

        $routeInfo->getHelperBaseName();
    }
}
