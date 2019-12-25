<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        $routes = $this->extractRouteInformations($routes);
        $routes = $this->removeNonRestfulRoutes($routes);
        $routes = $this->generateHelperNames($routes);
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
     * Extract useful informations from the route.
     */
    protected function extractRouteInformations(Collection $routes): Collection
    {
        return $routes->map(function (Route $route) {
            $parts = explode('.', $route->getName());

            $action = array_pop($parts);
            $resource = array_pop($parts);
            $nesting = array_reduce($parts, function ($acc, $part) {
                return $acc . Str::singular($part) . '_';
            }, '');

            return [
                'route_name' => $route->getName(),
                'action' => $action,
                'plural_resource' => $resource,
                'singular_resource' => Str::singular($resource),
                'nesting' => $nesting
            ];
        });
    }

    /**
     * Route helpers can only be generated for restful routes so we'll need to
     * remove the non restful ones.
     */
    protected function removeNonRestfulRoutes(Collection $routes): Collection
    {
        return $routes->filter(function (array $info) {
            return in_array($info['action'], [
                'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
            ]);
        });
    }

    /**
     * Generate the base helper names for the given routes.
     */
    protected function generateHelperNames(Collection $routes): Collection
    {
        return $routes->map(function (array $info) {
            $nesting = $info['nesting'];
            $pluralResource = $info['plural_resource'];
            $singularResource = $info['singular_resource'];

            $pluralName = "{$nesting}{$pluralResource}";
            $singularName = "{$nesting}{$singularResource}";

            switch ($info['action']) {
                case 'index':
                case 'store':
                    $baseHelperName = $pluralName;
                    break;
                case 'create':
                    $baseHelperName = "new_{$singularName}";
                    break;
                case 'edit':
                    $baseHelperName = "edit_{$singularName}";
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $baseHelperName = $singularName;
                    break;
                default:
                    $baseHelperName = false;
            }

            $info['base_helper_name'] = Str::camel($baseHelperName);

            return $info;
        })->filter(function (array $info) {
            // Remove routes for which we may have not been able to generate a
            // helper name.
            return $info['base_helper_name'];
        });
    }

    /**
     * Remove duplicate entries from the gathered info. (destroy, show, update)
     * all use the same route.
     */
    protected function removeDuplicates(Collection $routes)
    {
        return $routes->unique('base_helper_name');
    }
}
