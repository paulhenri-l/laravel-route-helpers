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
        $this->assertEquals('new_post', $createRoute->getHelperBaseName());
        $this->assertEquals('posts', $storeRoute->getHelperBaseName());
        $this->assertEquals('post', $showRoute->getHelperBaseName());
        $this->assertEquals('edit_post', $editRoute->getHelperBaseName());
        $this->assertEquals('post', $updateRoute->getHelperBaseName());
        $this->assertEquals('post', $destroyRoute->getHelperBaseName());
    }

    public function test_it_can_infer_the_helper_basename_for_irregular_words()
    {
        $indexRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.index')
        );

        $createRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.create')
        );

        $storeRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.store')
        );

        $showRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.show')
        );

        $editRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.edit')
        );

        $updateRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.update')
        );

        $destroyRoute = new Info(
            (new Route(['GET'], '/test', []))->name('people.destroy')
        );

        $this->assertEquals('people', $indexRoute->getHelperBaseName());
        $this->assertEquals('new_person', $createRoute->getHelperBaseName());
        $this->assertEquals('people', $storeRoute->getHelperBaseName());
        $this->assertEquals('person', $showRoute->getHelperBaseName());
        $this->assertEquals('edit_person', $editRoute->getHelperBaseName());
        $this->assertEquals('person', $updateRoute->getHelperBaseName());
        $this->assertEquals('person', $destroyRoute->getHelperBaseName());
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

        $this->assertEquals('post_comments', $indexRoute->getHelperBaseName());
        $this->assertEquals('new_post_comment', $createRoute->getHelperBaseName());
        $this->assertEquals('post_comments', $storeRoute->getHelperBaseName());
        $this->assertEquals('post_comment', $showRoute->getHelperBaseName());
        $this->assertEquals('edit_post_comment', $editRoute->getHelperBaseName());
        $this->assertEquals('post_comment', $updateRoute->getHelperBaseName());
        $this->assertEquals('post_comment', $destroyRoute->getHelperBaseName());
    }

    public function test_helper_name_on_non_restful_route()
    {
        $this->expectException(NonRestfulRouteException::class);

        $routeInfo = new Info(
            (new Route(['GET'], '/test', []))->name('posts.comments.hello')
        );

        $routeInfo->getHelperBaseName();
    }

    public function test_info_can_be_created_on_single_word_routes()
    {
        $routeInfo = new Info(
            (new Route(['GET'], '/test', []))->name('login')
        );

        $this->assertFalse($routeInfo->isValid());
    }

    public function test_is_valid_detects_non_restful_routes()
    {
        $routeInfo = new Info(
            (new Route(['GET'], '/test', []))->name('login.toto')
        );

        $this->assertFalse($routeInfo->isValid());
    }

    public function test_is_valid_detects_no_word_routes()
    {
        $routeInfo = new Info(
            (new Route(['GET'], '/test', []))->name('')
        );

        $this->assertFalse($routeInfo->isValid());
    }
}
