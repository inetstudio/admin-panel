<?php

Route::group([
    'namespace' => 'InetStudio\AdminPanel\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::post('/config/{key}', 'UtilitiesControllerContract@getConfig')->name('back.admin-panel.config.get');
    Route::get('/', 'PagesControllerContract@showIndexPage')->name('back');
});
