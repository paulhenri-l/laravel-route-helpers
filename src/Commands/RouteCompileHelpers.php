<?php

namespace PaulhenriL\LaravelRouteHelpers\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use PaulhenriL\LaravelRouteHelpers\Helpers\Generator;
use PaulhenriL\LaravelRouteHelpers\Helpers\Loader;

class RouteCompileHelpers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:compile-helpers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile a new helpers file for your routes';

    /**
     * The Router instance.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $generator = new Generator($this->router);

        $loader = new Loader(new Filesystem(), $generator, [
            'file_path' => base_path(config('route_helpers.helpers_path')),
            'recompilation_checks_enabled' => config('route_helpers.recompilation_checks_enabled')
        ]);

        $loader->forceRecompile();

        $this->info('Helper file compiled');
    }
}
