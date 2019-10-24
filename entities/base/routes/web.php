<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\AdminPanel\Base\Contracts\Http\Controllers\Front',
        'middleware' => ['web'],
    ],
    function () {
        Route::post('/view/{view?}', 'ViewsControllerContract@getView')->name('front.view.get');
    }
);
