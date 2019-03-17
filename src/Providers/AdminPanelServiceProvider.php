<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Collective\Html\FormBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
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
        $this->registerFormComponents();
        $this->registerBladeDirectives();
        $this->registerArrMacroses();
        $this->registerStrMacroses();
        $this->registerCarbonMacroses();
        $this->registerCollectionMacroses();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();

            $loader->alias('BindingsHelpers', 'InetStudio\AdminPanel\Helpers\BindingsHelpers');
        });
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
                'InetStudio\AdminPanel\Console\Commands\Utilities\GenerateBindingsCommand',
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
     * Регистрация компонентов форм.
     *
     * @return void
     */
    protected function registerFormComponents()
    {
        FormBuilder::component('info', 'admin::back.forms.blocks.info', ['name' => null, 'value' => null, 'attributes' => null]);
        FormBuilder::component('buttons', 'admin::back.forms.blocks.buttons', ['name', 'value', 'attributes']);

        FormBuilder::component('string', 'admin::back.forms.fields.string', ['name', 'value', 'attributes']);
        FormBuilder::component('passwords', 'admin::back.forms.fields.passwords', ['name', 'value', 'attributes']);
        FormBuilder::component('radios', 'admin::back.forms.fields.radios', ['name', 'value', 'attributes']);
        FormBuilder::component('checks', 'admin::back.forms.fields.checks', ['name', 'value', 'attributes']);
        FormBuilder::component('datepicker', 'admin::back.forms.fields.datepicker', ['name', 'value', 'attributes']);
        FormBuilder::component('wysiwyg', 'admin::back.forms.fields.wysiwyg', ['name', 'value', 'attributes']);
        FormBuilder::component('dropdown', 'admin::back.forms.fields.dropdown', ['name', 'value', 'attributes']);
    }

    /**
     * Регистрация директив blade.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';

            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false) {
                    $fullExpression = $namespace.'::'.$expression;

                    $result .= "<?php 
                        if (\$__env->exists('{$fullExpression}')) echo \$__env->make('{$fullExpression}', array_except(get_defined_vars(), array('__data', '__path')))->render();
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
     * Регистрация макросов Arr.
     */
    protected function registerArrMacroses()
    {
        Arr::macro('changeKeysCase', function (array $arr, int $case = CASE_LOWER) {
            $case = ($case == CASE_LOWER) ? MB_CASE_LOWER : MB_CASE_UPPER;

            $returnArray = [];

            foreach ($arr as $key => $value) {
                $returnArray[mb_convert_case($key, $case, 'UTF-8')] = $value;
            }

            return $returnArray;
        });

        Arr::macro('arraySumIdenticalKeys', function () {
            $arrays = func_get_args();
            $keys = array_keys(array_reduce($arrays, function ($keys, $arr) {
                return $keys + $arr;
            }, []));
            $sums = [];

            foreach ($keys as $key) {
                $sums[$key] = array_reduce($arrays, function ($sum, $arr) use ($key) {
                    return $sum + (isset($arr[$key])) ? $arr[$key] : 0;
                });
            }

            return $sums;
        });
    }

    /**
     * Регистрация макросов Str.
     */
    protected function registerStrMacroses()
    {
        Str::macro('hideEmail', function ($value) {
            $partials = explode("@", $value);
            $service = array_pop($partials);

            $name = implode($partials, '@');
            $nameLen = strlen($name);

            $startHidePos = floor($nameLen*0.33);
            $endHidePos = floor($nameLen*0.66);

            return (($nameLen == 1) ? '*' : (substr($name,0, $startHidePos)
                .str_repeat('*', ($endHidePos-$startHidePos))
                .substr($name,$endHidePos, $nameLen)))
                .'@'.$service;
        });
    }

    /**
     * Регистрация макросов Carbon.
     */
    protected function registerCarbonMacroses()
    {
        Carbon::macro('formatTime', function (string $strTime) {
            $monthsNames = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            $carbonTime = self::parse($strTime);

            $day = $carbonTime->day;
            $month = (isset($monthsNames[$carbonTime->month])) ? $monthsNames[$carbonTime->month] : '';
            $time = sprintf('%02d', $carbonTime->hour).':'.sprintf('%02d', $carbonTime->minute);

            return trim($day.' '.$month).', '.$time;
        });

        Carbon::macro('formatDateToRus', function (string $strTime) {
            $monthsNames = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            $carbonTime = self::parse($strTime);

            $day = $carbonTime->day;
            $month = (isset($monthsNames[$carbonTime->month])) ? $monthsNames[$carbonTime->month] : '';
            $year = $carbonTime->year;

            return trim($day.' '.$month).' '.$year;
        });
    }

    /**
     * Регистрация макросов Collection.
     */
    protected function registerCollectionMacroses()
    {
        Collection::macro('ksort', function () {
            krsort($this->items);

            return $this;
        });
    }
}
