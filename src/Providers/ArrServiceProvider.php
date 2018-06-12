<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

/**
 * Class ArrServiceProvider.
 */
class ArrServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
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
                    return $sum + @$arr[$key];
                });
            }

            return $sums;
        });
    }
}
