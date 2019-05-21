<?php

namespace InetStudio\AdminPanel\Base\Models\Traits\Scopes;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * Trait BuildQueryScopeTrait.
 */
trait BuildQueryScopeTrait
{
    protected static $buildQueryScopeDefaults = [
        'columns' => [],
        'relations' => [],
        'scopes' => [],
    ];

    /**
     * Устанавливаем параметры для построения запросов.
     *
     * @param  array  $params
     */
    public function setBuildQueryScopeParams(array $params = []): void
    {
        foreach ($params as $type => $param) {
            if ($type == 'columns') {
                self::$buildQueryScopeDefaults[$type] = array_merge(self::$buildQueryScopeDefaults[$type], $param);
            } else {
                self::$buildQueryScopeDefaults[$type] = array_replace_recursive(self::$buildQueryScopeDefaults[$type] ?? [], $param);
            }
        }
    }

    /**
     * Возвращаем запрос на получение объектов.
     *
     * @param Builder $query
     * @param array $params
     *
     * @return Builder
     */
    public function scopeBuildQuery(Builder $query, array $params = []): Builder
    {
        if (isset($params['withTrashed']) && $params['withTrashed']) {
            $query->withTrashed();
        }

        $columns = isset($params['columns'])
            ? array_unique(array_merge(self::$buildQueryScopeDefaults['columns'], $params['columns']))
            : self::$buildQueryScopeDefaults['columns'];
        $columns = $this->prepareColumns($columns);

        $query->select($columns);

        if (isset($params['relations'])) {
            $query->with(array_intersect_key(self::$buildQueryScopeDefaults['relations'], array_flip($params['relations'])));
        }

        foreach ($params['filter'] ?? [] as $filter) {
            $query = $filter($query);
        }

        if (isset($params['random'])) {
            $query->orderByRaw('RAND()');
        } else {
            foreach ($params['order'] ?? [] as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        if (isset($params['paging'])) {
            $skip = $params['paging']['page']*$params['paging']['limit'];

            $query->skip($skip)->limit($params['paging']['limit']);
        }

        foreach ($params['scopes'] ?? [] as $scopeName) {
            if (isset(self::$buildQueryScopeDefaults['scopes'][$scopeName])) {
                $query->withGlobalScope($scopeName, self::$buildQueryScopeDefaults['scopes'][$scopeName]);
            }
        }

        return $query;
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
        $table = $this->getTable();

        $preparedColumns = [];

        foreach ($columns as $column) {
            $preparedColumns[] = (Str::contains($column, '.') || ($column instanceof Expression)) ? $column : implode('.', [$table, $column]);
        }

        return $preparedColumns;
    }
}