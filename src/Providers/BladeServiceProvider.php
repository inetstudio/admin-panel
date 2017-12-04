<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';

            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false) {
                    $fullExpression = $namespace.'::'.$expression;
                    $alternativeExpression = $namespace.'::back.'.$expression;

                    $result .= "<?php 
                    
                        if (\$__env->exists('{$fullExpression}')) echo \$__env->make('{$fullExpression}', array_except(get_defined_vars(), array('__data', '__path')))->render();
                        if (\$__env->exists('{$alternativeExpression}')) echo \$__env->make('{$alternativeExpression}', array_except(get_defined_vars(), array('__data', '__path')))->render();
                     
                     ?>\r\n";
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

        Blade::if('env', function ($env) {
            return app()->environment($env);
        });

        Blade::directive('inline', function ($expression) {
            $include = "//  {$expression}\n".
                "<?php echo str_replace([
                        '(../assets/img',
                        '(../assets/fonts',
                        '(/assets/img',
                        '(/assets/fonts',
                    ], [
                        '('.static_asset('assets/img'),
                        '('.static_asset('assets/fonts'),
                        '('.static_asset('assets/img'),
                        '('.static_asset('assets/fonts'),
                    ], file_get_contents(public_path().{$expression})); ?>\n";

            if (ends_with($expression, ".html'")) {
                return $include;
            }
            if (ends_with($expression, ".css'")) {
                return "<style>\n".$include.'</style>';
            }
            if (ends_with($expression, ".js'")) {
                return "<script>\n".$include.'</script>';
            }
        });
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
