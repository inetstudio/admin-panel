<?php

namespace InetStudio\AdminPanel\Base\Transformers;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class BaseTransformer.
 */
class BaseTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $cacheKeys = [];

    /**
     * Ключи кэша.
     *
     * @param array $cacheKeys
     */
    public function addCacheKeys(array $cacheKeys = []): void
    {
        $this->cacheKeys = $cacheKeys;
    }

    /**
     * Добавляем ключи кэша в группу объекта.
     *
     * @param $item
     *
     * @throws BindingResolutionException
     */
    public function cache($item): void
    {
        $groupCacheKey = 'cacheKeys_'.md5(get_class($item).$item->id);
        app()->make('CacheService')->addKeysToCacheGroup($groupCacheKey, $this->cacheKeys);
    }

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}