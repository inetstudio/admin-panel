<?php

namespace InetStudio\AdminPanel\Listeners\SEO;

use Illuminate\Support\Facades\Cache;
use InetStudio\AdminPanel\Events\SEO\UpdateMetaEvent;

class ClearMetaCacheListener
{
    /**
     * ClearCacheListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UpdateMetaEvent $event
     * @return void
     */
    public function handle(UpdateMetaEvent $event)
    {
        $object = $event->object;

        Cache::forget('SEOService_getTags_'.md5(get_class($object).$object->id));
    }
}
