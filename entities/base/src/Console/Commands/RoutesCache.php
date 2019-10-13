<?php

namespace InetStudio\AdminPanel\Base\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\RouteCollection;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

/**
 * Class RoutesCache.
 */
class RoutesCache extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route:cache-separate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a route cache file for faster route registration';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->call('route:clear');

        $routes = $this->getFreshApplicationRoutes();

        if (count($routes) === 0) {
            return $this->error("Your application doesn't have any routes.");
        }

        $backRoutes = new RouteCollection();
        $frontRoutes = new RouteCollection();

        foreach ($routes as $route) {
            $route->prepareForSerialization();

            $params = $route->action;
            if (isset($params['as']) && Str::startsWith($params['as'], 'back')) {
                if ($params['as'] == 'back') {
                    $frontRoutes->add($route);
                }
            } elseif (isset($params['as']) && Str::startsWith($params['as'], 'front')) {
                $frontRoutes->add($route);
            } else {
                if (isset($params['prefix']) && (Str::startsWith($params['prefix'], '_ignition') || Str::startsWith($params['prefix'], '_debugbar'))) {
                    if (config('app.debug')) {
                        $frontRoutes->add($route);
                    }
                } else {
                    $frontRoutes->add($route);
                }
            }

            $backRoutes->add($route);
        }

        $this->files->put(
            $this->laravel->getCachedRoutesPath(), $this->buildRouteCacheFile($frontRoutes, $backRoutes)
        );

        $this->info('Routes cached successfully!');
    }

    /**
     * Boot a fresh copy of the application and get the routes.
     *
     * @return \Illuminate\Routing\RouteCollection
     */
    protected function getFreshApplicationRoutes()
    {
        return tap($this->getFreshApplication()['router']->getRoutes(), function ($routes) {
            $routes->refreshNameLookups();
            $routes->refreshActionLookups();
        });
    }

    /**
     * Get a fresh application instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function getFreshApplication()
    {
        return tap(require $this->laravel->bootstrapPath().'/app.php', function ($app) {
            $app->make(ConsoleKernelContract::class)->bootstrap();
        });
    }

    /**
     * @param RouteCollection $frontRoutes
     * @param RouteCollection $backRoutes
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildRouteCacheFile(RouteCollection $frontRoutes, RouteCollection $backRoutes)
    {
        $stub = $this->files->get(__DIR__.'/stubs/routes.stub');

        $stub = str_replace('{{frontRoutes}}', base64_encode(serialize($frontRoutes)), $stub);
        return str_replace('{{backRoutes}}', base64_encode(serialize($backRoutes)), $stub);
    }
}
