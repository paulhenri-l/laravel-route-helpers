<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class RouteInfoGatherer
{
    /**
     * The Router instance.
     *
     * @var Router
     */
    protected $router;

    /**
     * RouteInfoGatherer constructor.
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Gather info about registered routes.
     */
    public function gatherInfo(): array
    {
        $routes = collect($this->router->getRoutes());

        $routes = $this->removeUnnamedRoutes($routes);
        $routes = $this->convertToRouteInfoClass($routes);
        $routes = $this->removeNonRestfulRoutes($routes);
        $routes = $this->removeDuplicates($routes);

        return $routes->toArray();
    }

    /**
     * Remove unnamed routes as we can't generate helpers for them.
     */
    protected function removeUnnamedRoutes(Collection $routes): Collection
    {
        return $routes->filter(function (Route $route) {
            return $route->getName();
        });
    }

    /**
     * Convert the Route to a RouteInfo instance.
     */
    protected function convertToRouteInfoClass(Collection $routes): Collection
    {
        return $routes->map(function (Route $route) {
            return new RouteInfo($route);
        });
    }

    /**
     * Route helpers can only be generated for restful routes so we'll need to
     * remove the non restful ones.
     */
    protected function removeNonRestfulRoutes(Collection $routes): Collection
    {
        return $routes->filter(function (RouteInfo $routeInfo) {
            return $routeInfo->isRestful();
        });
    }

    /**
     * Remove duplicate entries from the gathered info. (destroy, show, update)
     * all use the same route so we only need one of them.
     */
    protected function removeDuplicates(Collection $routes)
    {
        return $routes->unique(function (RouteInfo $routeInfo) {
            return $routeInfo->getHelperBaseName();
        });
    }
}
