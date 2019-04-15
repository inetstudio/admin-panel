<?php

namespace InetStudio\AdminPanel\Base\Services;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Class BaseService.
 */
class BaseService implements BaseServiceContract
{
    /**
     * @var
     */
    protected $model;

    /**
     * BaseService constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Возвращаем модель.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Получаем объект по id.
     *
     * @param mixed $id
     * @param array $params
     *
     * @return mixed
     */
    public function getItemById($id = 0, array $params = [])
    {
        return $this->model::buildQuery($params)->find($id) ?? new $this->model;
    }

    /**
     * Получаем все объекты.
     *
     * @param array $params
     *
     * @return Builder
     */
    public function getAllItems(array $params = []): Builder
    {
        return $this->model::buildQuery($params);
    }

    /**
     * Сохраняем модель.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function saveModel(array $data, int $id = 0)
    {
        $item = $this->model::updateOrCreate(
            ['id' => $id],
            $data
        );

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param mixed $id
     *
     * @return bool|null
     */
    public function destroy($id): ?bool
    {
        return $this->model::destroy($id);
    }
}
