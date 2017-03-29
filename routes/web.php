<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Back', 'middleware' => 'auth'],function () {
    Route::get('/', 'IndexController@getIndex');

    Route::get('login', 'Auth\AuthController@showLoginForm');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');
});
