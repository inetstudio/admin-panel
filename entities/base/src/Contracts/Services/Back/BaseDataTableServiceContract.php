<?php

namespace InetStudio\AdminPanel\Base\Contracts\Services\Back;

use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Builder;

/**
 * Interface BaseDataTableServiceContract.
 */
interface BaseDataTableServiceContract
{
    /**
     * @return Builder
     */
    public function html(): Builder;

    /**
     * Запрос на получение данных таблицы.
     *
     * @return JsonResponse
     */
    public function ajax(): JsonResponse;

    /**
     * Запрос в бд для получения данных для формирования таблицы.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query();
}
