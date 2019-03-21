<?php

namespace InetStudio\AdminPanel\Base\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends ServiceProvider
{
    /**
    * @var bool
    */
    protected $defer = true;

    /**
    * @var array
    */
    public $bindings = [
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
