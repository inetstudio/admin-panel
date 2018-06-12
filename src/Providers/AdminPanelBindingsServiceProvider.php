<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AdminPanelBindingsServiceProvider.
 */
class AdminPanelBindingsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public $bindings = [
        // Controllers
        'InetStudio\AdminPanel\Contracts\Http\Controllers\Back\PagesControllerContract' => 'InetStudio\AdminPanel\Http\Controllers\Back\PagesController',

        // Responses
        'InetStudio\AdminPanel\Contracts\Http\Responses\Back\IndexResponseContract' => 'InetStudio\AdminPanel\Http\Responses\Back\IndexResponse',

        // Serializers
        'InetStudio\ACL\Passwords\Contracts\Services\Front\PasswordsServiceContract' => 'InetStudio\ACL\Passwords\Services\Front\PasswordsService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'InetStudio\AdminPanel\Contracts\Http\Controllers\Back\PagesControllerContract',
            'InetStudio\AdminPanel\Contracts\Http\Responses\Back\IndexResponseContract',
            'InetStudio\AdminPanel\Contracts\Serializers\SimpleDataArraySerializerContract',
        ];
    }
}
