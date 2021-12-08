<?php

namespace InetStudio\AdminPanel\Base\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerViewComponents();
    }

    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\AdminPanel\Base\Console\Commands\RoutesCache',
            ]);
        }
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'inetstudio.admin-panel.base');
    }

    protected function registerViewComponents()
    {
        Blade::componentNamespace('InetStudio\AdminPanel\Base\View\Components', 'inetstudio.admin-panel.base');
    }
}
