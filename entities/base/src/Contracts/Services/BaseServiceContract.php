<?php

namespace InetStudio\AdminPanel\Base\Contracts\Services;

use Illuminate\Database\Query\Builder;

/**
 * Interface BaseServiceContract.
 */
interface BaseServiceContract
{
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
    public function getAllItems(array $params = []): Builder;

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