<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back\Traits;

/**
 * Trait DatatablesTrait.
 */
trait DatatablesTrait
{
    /**
     * @param $entity
     * @param $type
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function generateTable($entity, $type)
    {
        $table = app('datatables.html');

        $table->columns($this->getColumns($entity, $type));
        $table->ajax($this->getAjaxOptions($entity, $type));
        $table->parameters($this->getTableParameters($entity, $type));

        return $table;
    }

    /**
     * Свойства колонок datatables.
     *
     * @param $entity
     * @param $type
     *
     * @return array
     */
    private function getColumns($entity, $type): array
    {
        return (config($entity.'.datatables.columns.'.$type)) ? config($entity.'.datatables.columns.'.$type) : [];
    }

    /**
     * Свойства ajax datatables.
     *
     * @param $entity
     * @param $type
     *
     * @return array
     */
    private function getAjaxOptions($entity, $type): array
    {
        $options = (config($entity.'.datatables.ajax.'.$type)) ? config($entity.'.datatables.ajax.'.$type) : [];

        $params = (isset($options['url_params'])) ? $options['url_params'] : [];
        $options['url'] = (isset($options['url'])) ? route($options['url'], $params) : '';

        return $options;
    }

    /**
     * Свойства datatables.
     *
     * @param $entity
     * @param $type
     *
     * @return array
     */
    private function getTableParameters($entity, $type): array
    {
        $i18n = trans('admin::datatables');

        $options = (config($entity.'.datatables.table.'.$type)) ? config($entity.'.datatables.table.'.$type) : ((config($entity.'.datatables.table.default')) ? config($entity.'.datatables.table.default') : []);
        $options['language'] = $i18n;

        return $options;
    }
}
