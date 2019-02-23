<?php

namespace InetStudio\AdminPanel\Services;

/**
 * Class CommonService.
 */
class CommonService
{
    /**
     * @var
     */
    public $repository;

    public $model;

    /**
     * CommonService constructor.
     *
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получаем объект по id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getItemById(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Получаем все объекты.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function getAllItems(array $params = [])
    {
        return $this->repository->getAllItems($params);
    }
}
