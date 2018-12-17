<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\AdminPanel\Contracts\Http\Responses\Back\ConfigResponseContract;
use InetStudio\AdminPanel\Contracts\Http\Controllers\Back\UtilitiesControllerContract;

/**
 * Class UtilitiesController.
 */
class UtilitiesController extends Controller implements UtilitiesControllerContract
{
    /**
     * Возвращаем значения из конфига.
     *
     * @param string $key
     *
     * @return ConfigResponseContract
     */
    public function getConfig(string $key): ConfigResponseContract
    {
        $value = config($key);

        return app()->makeWith(ConfigResponseContract::class, [
            'data' => $value,
        ]);
    }
}
