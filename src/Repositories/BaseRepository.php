<?php

namespace InetStudio\AdminPanel\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use InetStudio\AdminPanel\Contracts\Repositories\BaseRepositoryContract;

/**
 * Class BaseRepository.
 */
class BaseRepository implements BaseRepositoryContract
{
    /**
     * @var
     */
    public $model;

    /**
     * @var array
     */
    public $defaultColumns = [];

    /**
     * @var array
     */
    public $relations = [];

    /**
     * @var array
     */
    public $scopes = [];

    /**
     * Получаем модель репозитория.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Возвращаем пустой объект по id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getEmptyObjectById(int $id)
    {
        return $this->model::select(['id'])->where('id', '=', $id)->first();
    }

    /**
     * Возвращаем объект по id, либо создаем новый.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getItemByID(int $id)
    {
        return $this->model::find($id) ?? new $this->model;
    }

    /**
     * Возвращаем удаленный объект по id, либо пустой.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getTrashedItemByID(int $id = 0)
    {
        return $this->model::onlyTrashed()->find($id) ?? new $this->model;
    }

    /**
     * Возвращаем объекты по списку id.
     *
     * @param $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->whereIn('id', (array) $ids);

        return $builder->get();
    }

    /**
     * Сохраняем объект.
     *
     * @param array $data
     * @param int $id
     *
     * @return mixed
     */
    public function save(array $data, int $id = 0)
    {
        $item = $this->getItemByID($id);
        $item->fill($data);
        $item->save();

        return $item;
    }

    /**
     * Удаляем объект.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(int $id = 0): ?bool
    {
        return $this->getItemByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param array $conditions
     * @param array $params
     *
     * @return mixed
     */
    public function searchItems(array $conditions, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->where($conditions);

        return $builder->get();
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
        $builder = $this->getItemsQuery($params);

        return $builder->get();
    }

    /**
     * Возвращаем запрос на получение объектов.
     *
     * @param array $params
     *
     * @return Builder
     */
    public function getItemsQuery(array $params = []): Builder
    {
        $builder = $this->model::query();

        if (isset($params['withTrashed']) && $params['withTrashed']) {
            $builder->withTrashed();
        }

        $columns = isset($params['columns']) ? array_unique(array_merge($this->defaultColumns, $params['columns'])) : $this->defaultColumns;
        $columns = $this->prepareColumns($columns);

        $builder->select($columns);

        if (isset($params['relations'])) {
            $builder->with(array_intersect_key($this->relations, array_flip($params['relations'])));
        }

        foreach ($params['order'] ?? [] as $column => $direction) {
            $builder->orderBy($column, $direction);
        }

        if (isset($params['paging'])) {
            $skip = $params['paging']['page']*$params['paging']['limit'];

            $builder->skip($skip)->limit($params['paging']['limit']);
        }

        foreach ($params['scopes'] ?? [] as $scopeName) {
            if (isset($this->scopes[$scopeName])) {
                $builder->withGlobalScope($scopeName, $this->scopes[$scopeName]);
            }
        }

        return $builder;
    }

    /**
     * Подготавливаем колонки.
     *
     * @param array $columns
     *
     * @return array
     */
    protected function prepareColumns(array $columns): array
    {
        $table = $this->model->getTable();

        $preparedColumns = [];

        foreach ($columns as $column) {
            $preparedColumns[] = (str_contains($column, '.') || ($column instanceof Expression)) ? $column : implode('.', [$table, $column]);
        }

        return $preparedColumns;
    }
}
