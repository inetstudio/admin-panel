<?php

namespace InetStudio\AdminPanel;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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

        \Form::component('string', 'admin::forms.fields.string', ['name', 'value', 'attributes']);
        \Form::component('datepicker', 'admin::forms.fields.datepicker', ['name', 'value', 'attributes']);
        \Form::component('wysiwyg', 'admin::forms.fields.wysiwyg', ['name', 'value', 'attributes']);
        \Form::component('dropdown', 'admin::forms.fields.dropdown', ['name', 'value', 'attributes']);

        \Form::component('info', 'admin::forms.blocks.info', ['name' => null, 'value' => null, 'attributes' => null]);
        \Form::component('buttons', 'admin::forms.blocks.buttons', ['name', 'value', 'attributes']);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');
        $this->app->register('Laravelista\Ekko\EkkoServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Form', 'Collective\Html\FormFacade');
        $loader->alias('Html', 'Collective\Html\HtmlFacade');
        $loader->alias('Ekko', 'Laravelista\Ekko\Facades\Ekko');
    }
}
