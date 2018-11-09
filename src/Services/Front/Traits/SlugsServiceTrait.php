<?php

namespace InetStudio\AdminPanel\Services\Front\Traits;

/**
 * Trait SlugsServiceTrait.
 */
trait SlugsServiceTrait
{
    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, array $params = [])
    {
        return $this->repository->getItemBySlug($slug, $params);
    }
}
