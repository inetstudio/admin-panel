<?php

Route::group([
    'namespace' => 'InetStudio\AdminPanel\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::get('/', 'PagesControllerContract@showIndexPage')->name('back');
});
