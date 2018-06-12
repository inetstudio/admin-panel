<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\AdminPanel\Contracts\Http\Responses\Back\IndexResponseContract;
use InetStudio\AdminPanel\Contracts\Http\Controllers\Back\PagesControllerContract;

/**
 * Class PagesController.
 */
class PagesController extends Controller implements PagesControllerContract
{
    /**
     * Отображаем главную страницу административной панели.
     *
     * @return IndexResponseContract
     */
    public function showIndexPage(): IndexResponseContract
    {
        return app()->makeWith('InetStudio\AdminPanel\Contracts\Http\Responses\Back\IndexResponseContract', [
            'data' => [],
        ]);
    }
}
