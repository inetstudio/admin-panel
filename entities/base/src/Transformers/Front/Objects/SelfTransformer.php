<?php

namespace InetStudio\AdminPanel\Base\Transformers\Front\Objects;

use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;

/**
 * Class SelfTransformer.
 */
class SelfTransformer extends BaseTransformer
{
    /**
     * Подготовка данных для объектов.
     *
     * @param $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform($item): array
    {
        $emptyItem = $item->newInstance([], true);
        $emptyItem->id = $item->id;

        return [
            'empty' => $emptyItem,
        ];
    }
}
