<?php

namespace InetStudio\AdminPanel\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
    * @var array
    */
    public $bindings = [
        'InetStudio\AdminPanel\Base\Contracts\Http\Controllers\Front\ViewsControllerContract' => 'InetStudio\AdminPanel\Base\Http\Controllers\Front\ViewsController',
        'InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract' => 'InetStudio\AdminPanel\Base\Serializers\SimpleDataArraySerializer',
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
