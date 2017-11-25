<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Отображаем главную страницу административной панели.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showIndexPage(): View
    {
        return view('admin::back.pages.index');
    }
}
