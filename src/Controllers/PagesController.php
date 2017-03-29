<?php

namespace InetStudio\AdminPanel\Controllers;

use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function showIndexPage()
    {
        return view('admin::pages.index');
    }
}
