<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AdminPanelBindingsServiceProvider.
 */
class AdminPanelBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\AdminPanel\Contracts\Http\Controllers\Back\PagesControllerContract' => 'InetStudio\AdminPanel\Http\Controllers\Back\PagesController',
        'InetStudio\AdminPanel\Contracts\Http\Responses\Back\IndexResponseContract' => 'InetStudio\AdminPanel\Http\Responses\Back\IndexResponse',
        'InetStudio\AdminPanel\Contracts\Repositories\BaseRepositoryContract' => 'InetStudio\AdminPanel\Repositories\BaseRepository',
        'InetStudio\AdminPanel\Contracts\Serializers\SimpleDataArraySerializerContract' => 'InetStudio\AdminPanel\Serializers\SimpleDataArraySerializer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
