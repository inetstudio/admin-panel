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
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin');
    }

    /**
     * Регистрация переводов.
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'admin');
    }

    /**
     * Регистрация компонентов форм.
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

     */
    protected function registerBladeDirectives()
    {
        Blade::directive('loadFromModules', function ($expression) {
            $namespaces = view()->getFinder()->getHints();

            $result = '';

            foreach ($namespaces as $namespace => $paths) {
                if (strpos($namespace, 'admin.module') !== false || strpos($namespace, 'inetstudio.') !== false) {
                    $fullExpression = $namespace.'::'.$expression;

                    $result .= "<?php 
                        if (\$__env->exists('{$fullExpression}')) echo \$__env->make('{$fullExpression}', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render();
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

        Blade::if('monitoring', function () {
            $monitoringStop = \Carbon\Carbon::createFromTimestamp(config('app.release_time', time()), config('app.timezone'))->addDays(3);
            $now = Carbon::now();

            return $monitoringStop->greaterThan($now);
        });

        Blade::directive('inline', function ($expression) {
            if (! file_exists(public_path(str_replace("'", '', $expression)))) {
                return '';
            }

            $include =
                "<?php echo str_replace([
                        '(../assets/img',
                        '(../assets/fonts',
                        '(/assets/img',
                        '(/assets/fonts',
                    ], [
                        '('.asset('assets/img'),
                        '('.asset('assets/fonts'),
                        '('.asset('assets/img'),
                        '('.asset('assets/fonts'),
                    ], file_get_contents(public_path().{$expression})); ?>\n";

            if (Str::endsWith($expression, ".html'")) {
                return $include;
            }
            if (Str::endsWith($expression, ".css'")) {
                return "<style>\n".$include.'</style>';
            }
            if (Str::endsWith($expression, ".js'")) {
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
                $sums[$key] = 0;

                foreach ($arrays as $arr) {
                    $sums[$key] = $sums[$key] + ((isset($arr[$key])) ? $arr[$key] : 0);
                }
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

            $name = implode('@', $partials);
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
