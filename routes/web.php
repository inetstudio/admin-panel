<?php

Route::group(['middleware' => 'web'], function () {
    Route::group(['namespace' => 'InetStudio\AdminPanel\Http\Controllers\Back'], function () {
        Route::group(['prefix' => 'back'], function () {
            Route::group(['namespace' => 'Auth'], function () {
                Route::get('login', 'LoginController@showLoginForm')->name('back.login.form');
                Route::post('login', 'LoginController@login')->name('back.login');

                Route::group(['middleware' => 'back.auth'], function () {
                    Route::post('logout', 'LoginController@logout')->name('back.logout');
                });
            });

            Route::group(['middleware' => ['back.auth']], function () {
                Route::group(['namespace' => 'ACL', 'prefix' => 'acl'], function () {
                    Route::post('roles/suggestions', 'RolesController@getSuggestions')->name('back.acl.roles.getSuggestions');
                    Route::any('roles/data', 'RolesController@data')->name('back.acl.roles.data');
                    Route::resource('roles', 'RolesController', ['except' => [
                        'show',
                    ], 'as' => 'back.acl']);

                    Route::post('permissions/suggestions', 'PermissionsController@getSuggestions')->name('back.acl.permissions.getSuggestions');
                    Route::any('permissions/data', 'PermissionsController@data')->name('back.acl.permissions.data');
                    Route::resource('permissions', 'PermissionsController', ['except' => [
                        'show',
                    ], 'as' => 'back.acl']);

                    Route::post('users/suggestions', 'UsersController@getSuggestions')->name('back.acl.users.getSuggestions');
                    Route::any('users/data', 'UsersController@data')->name('back.acl.users.data');
                    Route::resource('users', 'UsersController', ['except' => [
                        'show',
                    ], 'as' => 'back.acl']);
                });

                Route::group(['namespace' => 'Uploads'], function () {
                    Route::post('upload', 'UploadsController@upload')->name('back.upload');
                });

                Route::get('/', 'PagesController@showIndexPage')->name('back');
            });
        });
    });

    Route::group(['namespace' => 'InetStudio\AdminPanel\Http\Controllers\Front'], function () {
        Route::group(['namespace' => 'Images'], function () {
            Route::get('img/{id}', 'ImagesController@getImage')->name('front.image.get');
        });

        Route::group(['namespace' => 'Auth'], function () {
            Route::get('account/activate/{token?}', 'ActivateController@activate')->name('front.account.activate.get');
            Route::get('oauth/email', 'SocialLoginController@askEmail')->name('front.oauth.email');
            Route::post('oauth/email/approve', 'SocialLoginController@approveEmail')->name('front.oauth.email.approve');
            Route::get('oauth/{provider}', 'SocialLoginController@redirectToProvider')->name('front.oauth.login');
            Route::get('oauth/{provider}/callback', 'SocialLoginController@handleProviderCallback')->name('front.oauth.callback');
            Route::post('login', 'LoginController@loginCustom')->name('front.auth.login');
            Route::group(['middleware' => 'auth'], function () {
                Route::post('logout', 'LoginController@logout')->name('front.auth.logout');
            });
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmailCustom')->name('front.password.email');
            Route::get('password/reset/{token?}', 'ResetPasswordController@showResetForm')->name('front.password.reset.get');
            Route::post('password/reset', 'ResetPasswordController@resetCustom')->name('front.password.reset.post');
            Route::post('register', 'RegisterController@registerCustom')->name('front.register');
        });
    });
});
