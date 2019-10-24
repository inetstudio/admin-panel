<?php

namespace InetStudio\AdminPanel\Base\Http\Controllers\Front;

use Illuminate\View\View;
use Illuminate\Http\Request;
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
     * @param  Request  $request
     * @param  string  $view
     *
     * @return View
     */
    public function getView(Request $request, string $view = '')
    {
        $view = $view ?: $request->get('view', '');

        if (! view()->exists($view)) {
            abort(404);
        }

        $data = request()->except('view');

        return view($view, $data);
    }
}
