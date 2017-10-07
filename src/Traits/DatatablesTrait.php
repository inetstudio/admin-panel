<?php

namespace InetStudio\AdminPanel\Traits;

use Yajra\DataTables\DataTables;

trait DatatablesTrait
{
    /**
     * @param DataTables $dataTable
     * @param $entity
     * @param $type
     * @return mixed
     */
    private function generateTable(DataTables $dataTable, $entity, $type)
    {
        $table = $dataTable->getHtmlBuilder();

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
     * @return array
     */
    private function getColumns($entity, $type)
    {
        return (config($entity.'.datatables.columns.'.$type)) ? config($entity.'.datatables.columns.'.$type) : [];
    }

    /**
     * Свойства ajax datatables.
     *
     * @param $entity
     * @param $type
     * @return array
     */
    private function getAjaxOptions($entity, $type)
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
     * @return array
     */
    private function getTableParameters($entity, $type)
    {
        $options = (config($entity.'.datatables.table.'.$type)) ? config($entity.'.datatables.table.'.$type) : ((config($entity.'.datatables.table.default')) ? config($entity.'.datatables.table.default') : []);
        $options['language']['url'] = (isset($options['language']['url'])) ? asset($options['language']['url']) : '';

        return $options;
    }
}
