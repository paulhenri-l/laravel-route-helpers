<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Routing\Router;

class HelpersGenerator
{
    /**
     * The path route helper function blueprint.
     */
    protected const PATH_HELPER = <<<PHP
if (!function_exists('HELPER_NAME_path')) {
    function HELPER_NAME_path(\$params = [], \$absolute = true)
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

        foreach ($routesInfo as $routeInfo) {
            $function = str_replace(
                'HELPER_NAME',
                $routeInfo['base_helper_name'],
                static::PATH_HELPER
            );

            $function = str_replace(
                'ROUTE_NAME',
                $routeInfo['route_name'],
                $function
            );

            $functions .= $function . "\n\n";
        }

        return $functions;
    }
}