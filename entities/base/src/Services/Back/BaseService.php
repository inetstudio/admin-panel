<?php

namespace InetStudio\AdminPanel\Base\Services\Back;

use InetStudio\AdminPanel\Base\Services\CommonService;

/**
 * Class BaseService.
 */
class BaseService extends CommonService
{
    /**
     * BaseService constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
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
