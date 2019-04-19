<?php

namespace InetStudio\AdminPanel\Base\Services\Traits;

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
        return $this->model->buildQuery($params)->whereSlug($slug)->first();
    }
}
