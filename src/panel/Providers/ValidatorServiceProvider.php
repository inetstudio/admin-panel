<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
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
