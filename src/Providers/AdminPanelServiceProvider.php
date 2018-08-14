<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AdminPanelServiceProvider.
 */
class AdminPanelServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerHelpers();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\AdminPanel\Console\Commands\SetupCommand',
            ]);
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin');
    }

    /**
     * Регистрация переводов.
     *
     * @return void
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'admin');
    }

    /**
     * Регистрация хелперов.
     *
     * @return void
     */
    protected function registerHelpers(): void
    {
        $file = app_path(__DIR__.'/../../src/helpers.php');
        
        if (file_exists($file)) {
            require_once($file);
        }
    }
}
