<?php

namespace InetStudio\AdminPanel\Base\Contracts\Services;

/**
 * Interface BaseServiceContract.
 */
interface BaseServiceContract
{
    /**
     * Возвращаем модель.
     *
     * @return mixed
     */
    public function getModel();

    /**
     * Получаем объект по id.
     *
     * @param mixed $id
     * @param array $params
     *
     * @return mixed
     */
    public function getItemById($id = 0, array $params = []);

    /**
     * Получаем все объекты.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function getAllItems(array $params = []);

    /**
     * Сохраняем модель.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function saveModel(array $data, int $id = 0);

    /**
     * Удаляем модель.
     *
     * @param mixed $id
     *
     * @return bool|null
     */
    public function destroy($id): ?bool;
}