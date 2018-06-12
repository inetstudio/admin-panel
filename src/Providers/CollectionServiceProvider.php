<?php

namespace InetStudio\AdminPanel\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

/**
 * Class CollectionServiceProvider.
 */
class CollectionServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        Collection::macro('ksort', function () {
            krsort($this->items);

            return $this;
        });
    }
}
