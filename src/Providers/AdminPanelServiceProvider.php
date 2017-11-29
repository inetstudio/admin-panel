<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laratrust\Middleware\LaratrustRole;
use Laratrust\Middleware\LaratrustAbility;
use Laratrust\Middleware\LaratrustPermission;
use SocialiteProviders\Manager\SocialiteWasCalled;
use InetStudio\AdminPanel\Events\SEO\UpdateMetaEvent;
use InetStudio\AdminPanel\Console\Commands\SetupCommand;
use InetStudio\AdminPanel\Services\Front\SEO\SEOService;
use InetStudio\AdminPanel\Events\Images\UpdateImageEvent;
use InetStudio\AdminPanel\Services\Front\ACL\UsersService;
use SocialiteProviders\VKontakte\VKontakteExtendSocialite;
use InetStudio\AdminPanel\Events\Auth\UnactivatedLoginEvent;
use InetStudio\AdminPanel\Console\Commands\CreateAdminCommand;
use InetStudio\AdminPanel\Listeners\SEO\ClearMetaCacheListener;
use InetStudio\AdminPanel\Console\Commands\CreateFoldersCommand;
use InetStudio\AdminPanel\Listeners\Images\ClearImageCacheListener;
use InetStudio\AdminPanel\Http\Middleware\Back\Auth\AdminAuthenticate;
use InetStudio\AdminPanel\Services\Front\Auth\UsersActivationsService;
use JhaoDa\SocialiteProviders\Odnoklassniki\OdnoklassnikiExtendSocialite;
use InetStudio\AdminPanel\Listeners\Auth\SendActivateNotificationListener;
use InetStudio\AdminPanel\Http\Middleware\Back\Auth\RedirectIfAuthenticated;

class AdminPanelServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerMiddlewares($router);
        $this->registerEvents();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
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
                SetupCommand::class,
                CreateAdminCommand::class,
                CreateFoldersCommand::class,
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../public' => public_path(),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../config/admin.php' => config_path('admin.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateUsersTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_users_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_users_tables.php'),
                ], 'migrations');
            }
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
     * Регистрация посредников.
     *
     * @param Router $router
     * @return void
     */
    protected function registerMiddlewares(Router $router): void
    {
        $router->aliasMiddleware('back.auth', AdminAuthenticate::class);
        $router->aliasMiddleware('back.guest', RedirectIfAuthenticated::class);

        $router->aliasMiddleware('role', LaratrustRole::class);
        $router->aliasMiddleware('permission', LaratrustPermission::class);
        $router->aliasMiddleware('ability', LaratrustAbility::class);
    }

    /**
     * Регистрация событий.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(Registered::class, SendActivateNotificationListener::class);
        Event::listen(UnactivatedLoginEvent::class, SendActivateNotificationListener::class);
        Event::listen(UpdateMetaEvent::class, ClearMetaCacheListener::class);
        Event::listen(UpdateImageEvent::class, ClearImageCacheListener::class);
        Event::listen(SocialiteWasCalled::class, VKontakteExtendSocialite::class);
        Event::listen(SocialiteWasCalled::class, OdnoklassnikiExtendSocialite::class);
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        $this->app->register('JildertMiedema\LaravelPlupload\LaravelPluploadServiceProvider');
        $this->app->register('Phoenix\EloquentMeta\ServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Plupload', 'JildertMiedema\LaravelPlupload\Facades\Plupload');

        $this->app->bind('SEOService', SEOService::class);
        $this->app->bind('UsersActivationsService', UsersActivationsService::class);
        $this->app->singleton('UsersService', UsersService::class);
    }
}
