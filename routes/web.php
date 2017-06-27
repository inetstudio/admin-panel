<?php

Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
    Route::group(['namespace' => 'InetStudio\AdminPanel\Controllers'], function () {
        Route::group(['namespace' => 'Auth'], function () {
            Route::get('login', 'LoginController@showLoginForm')->name('back.login.form');
            Route::post('login', 'LoginController@login')->name('back.login');

            Route::group(['middleware' => 'back.auth'], function () {
                Route::post('logout', 'LoginController@logout')->name('back.logout');
            });
        });

        Route::group(['middleware' => ['back.auth']], function () {
            Route::group(['namespace' => 'ACL', 'prefix' => 'acl'], function () {
                Route::resource('roles', 'RolesController', ['except' => [
                    'show'
                ], 'as' => 'back.acl']);

                Route::resource('permissions', 'PermissionsController', ['except' => [
                    'show'
                ], 'as' => 'back.acl']);

                Route::resource('users', 'UsersController', ['except' => [
                    'show'
                ], 'as' => 'back.acl']);
            });

            Route::get('/', 'PagesController@showIndexPage')->name('back');
        });
    });
});
