<?php

namespace InetStudio\AdminPanel\Repositories\Traits;

/**
 * Trait SlugsRepositoryTrait.
 */
trait SlugsRepositoryTrait
{
    /**
     * Получаем объекты по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->whereSlug($slug);

        $item = $builder->first();

        return $item;
    }
}
