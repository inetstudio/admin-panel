<?php

Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {

    Route::group(['namespace' => 'InetStudio\AdminPanel\Controllers\Auth'], function () {
        Route::get('login', 'LoginController@showLoginForm')->name('admin.login.form');
        Route::post('login', 'LoginController@login')->name('admin.login');

        Route::group(['middleware' => 'admin.auth'], function() {
            Route::post('logout', 'LoginController@logout')->name('admin.logout');
        });
    });

    Route::group(['middleware' => 'admin.auth', 'namespace' => 'InetStudio\AdminPanel\Controllers'], function () {
        Route::get('/', 'PagesController@showIndexPage');
    });
});
