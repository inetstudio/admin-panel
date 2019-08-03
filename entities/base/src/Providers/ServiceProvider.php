<?php

namespace InetStudio\AdminPanel\Base\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerRoutes();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\AdminPanel\Base\Console\Commands\RoutesCache',
            ]);
        }
    }

    /**
     * Регистрация путей.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
