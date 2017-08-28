<?php

namespace InetStudio\AdminPanel;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AdminPanelServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__.'/../public' => public_path(),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../resources/views/admin', 'admin');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->mergeConfigFrom(
            __DIR__.'/../config/filesystems.php', 'filesystems.disks'
        );

        $router->aliasMiddleware('back.auth', 'InetStudio\AdminPanel\Middleware\AdminAuthenticate');
        $router->aliasMiddleware('role', 'Laratrust\Middleware\LaratrustRole');
        $router->aliasMiddleware('permission', 'Laratrust\Middleware\LaratrustPermission');
        $router->aliasMiddleware('ability', 'Laratrust\Middleware\LaratrustAbility');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\SetupCommand::class,
                Commands\CreateAdminCommand::class,
                Commands\CreateFoldersCommand::class,
            ]);
        }

        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';
            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false) {
                    $fullExpression = $namespace.'::'.$expression;
                    $result .= "<?php echo \$__env->make('{$fullExpression}', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
                }
            }

            return $result;
        });

        Blade::directive('pushonce', function ($expression) {
            $domain = explode(':', trim(substr($expression, 1, -1)));
            $push_name = $domain[0];
            $push_sub = $domain[1];
            $isDisplayed = '__pushonce_'.$push_name.'_'.$push_sub;
            return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush('{$push_name}'); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        Validator::extend('crop_size', function ($attribute, $value, $parameters, $validator) {
            $crop = json_decode($value, true);

            switch ($parameters[2]) {
                case 'min':
                    if (round($crop['width']) < $parameters[0] or round($crop['height']) < $parameters[1]) {
                        return false;
                    }
                    break;
                case 'fixed':
                    if (round($crop['width']) != $parameters[0] and round($crop['height']) != $parameters[1]) {
                        return false;
                    }
                    break;
            }

            return true;
        });

        \Form::component('info', 'admin::forms.blocks.info', ['name' => null, 'value' => null, 'attributes' => null]);
        \Form::component('buttons', 'admin::forms.blocks.buttons', ['name', 'value', 'attributes']);

        \Form::component('string', 'admin::forms.fields.string', ['name', 'value', 'attributes']);
        \Form::component('passwords', 'admin::forms.fields.passwords', ['name', 'value', 'attributes']);
        \Form::component('radios', 'admin::forms.fields.radios', ['name', 'value', 'attributes']);
        \Form::component('checks', 'admin::forms.fields.checks', ['name', 'value', 'attributes']);
        \Form::component('datepicker', 'admin::forms.fields.datepicker', ['name', 'value', 'attributes']);
        \Form::component('wysiwyg', 'admin::forms.fields.wysiwyg', ['name', 'value', 'attributes']);
        \Form::component('dropdown', 'admin::forms.fields.dropdown', ['name', 'value', 'attributes']);
        \Form::component('crop', 'admin::forms.fields.crop', ['name', 'value', 'attributes']);
        \Form::component('list', 'admin::forms.fields.list', ['name', 'value', 'attributes']);

        \Form::component('meta', 'admin::forms.groups.meta', ['name' => null, 'value' => null, 'attributes' => null]);
        \Form::component('social_meta', 'admin::forms.groups.social_meta', ['name' => null, 'value' => null, 'attributes' => null]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');
        $this->app->register('Cviebrock\EloquentSluggable\ServiceProvider');
        $this->app->register('JildertMiedema\LaravelPlupload\LaravelPluploadServiceProvider');
        $this->app->register('Laratrust\LaratrustServiceProvider');
        $this->app->register('Laravelista\Ekko\EkkoServiceProvider');
        $this->app->register('Phoenix\EloquentMeta\ServiceProvider');
        $this->app->register('Spatie\MediaLibrary\MediaLibraryServiceProvider');
        $this->app->register('Yajra\Datatables\HtmlServiceProvider');
        $this->app->register('Yajra\Datatables\DatatablesServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Ekko', 'Laravelista\Ekko\Facades\Ekko');
        $loader->alias('Form', 'Collective\Html\FormFacade');
        $loader->alias('Html', 'Collective\Html\HtmlFacade');
        $loader->alias('Laratrust', 'Laratrust\LaratrustFacade');
        $loader->alias('Plupload', 'JildertMiedema\LaravelPlupload\Facades\Plupload');
    }
}
