<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Routing\Router;

class HelpersGenerator
{
    /**
     * The path route helper function blueprint.
     */
    protected const PATH_HELPER = <<<PHP
if (!function_exists('HELPER_NAMEPath')) {
    function HELPER_NAMEPath(\$params = [], \$absolute = true)
    {
        return route('ROUTE_NAME', \$params, \$absolute);
    }
}
PHP;

    /**
     * The Router instance.
     *
     * @var Router
     */
    protected $router;

    /**
     * HelpersGenerator constructor.
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Generate the helper functions.
     */
    public function generateHelpers(): string
    {
        $routesInfo = (new RouteInfoGatherer($this->router))->gatherInfo();

        $functions = $this->generateHelperFunctions($routesInfo);

        return '<?php' . $functions;
    }

    /**
     * Generate the real helper functions.
     */
    protected function generateHelperFunctions(array $routesInfo): string
    {
        $functions = "\n";

        /** @var RouteInfo $routeInfo */
        foreach ($routesInfo as $routeInfo) {
            $function = "\n";

            $function .= str_replace(
                'HELPER_NAME',
                $routeInfo->getHelperBaseName(),
                static::PATH_HELPER
            );

            $function = str_replace(
                'ROUTE_NAME',
                $routeInfo->getRouteName(),
                $function
            );

            $functions .= $function . "\n";
        }

        return $functions;
    }
}
