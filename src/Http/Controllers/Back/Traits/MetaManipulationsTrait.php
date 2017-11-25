<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back\Traits;

trait MetaManipulationsTrait
{
    /**
     * Сохраняем мета теги.
     *
     * @param $item
     * @param $request
     */
    private function saveMeta($item, $request): void
    {
        if ($request->filled('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $item->updateMeta($key, $value);
            }

            \Event::fire('inetstudio.seo.cache.clear', $item);
        }
    }
}
