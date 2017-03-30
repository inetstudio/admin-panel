<?php

namespace InetStudio\AdminPanel;

use Illuminate\Support\Facades\Blade;
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

        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';
            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false) {
                    $fullExpression = $namespace . '::' . $expression;
                    $result .= "<?php echo \$__env->make('{$fullExpression}', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
                }
            }

            return $result;
        });
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
