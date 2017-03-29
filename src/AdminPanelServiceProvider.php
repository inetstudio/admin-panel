<?php

namespace InetStudio\AdminPanel;

use Illuminate\Support\ServiceProvider;
use \Illuminate\Routing\Router;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__.'/../public' => public_path(),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../resources/views/admin', 'admin');

        $router->aliasMiddleware('admin.auth', 'InetStudio\AdminPanel\Middleware\AdminAuthenticate');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
