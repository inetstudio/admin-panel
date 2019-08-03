<?php

namespace InetStudio\AdminPanel\Base\Http\Controllers\Front;

use Illuminate\View\View;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\AdminPanel\Base\Contracts\Http\Controllers\Front\ViewsControllerContract;

/**
 * Class ViewsController.
 */
class ViewsController extends Controller implements ViewsControllerContract
{
    /**
     * Возвращаем содержимое представления.
     *
     * @param  string  $view
     *
     * @return View
     */
    public function getView(string $view)
    {
        if (! view()->exists($view)) {
            abort(404);
        }

        $data = request()->all();

        return view($view, $data);
    }
}
