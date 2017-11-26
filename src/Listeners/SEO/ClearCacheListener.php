<?php

namespace InetStudio\AdminPanel\Listeners\SEO;

use Illuminate\Support\Facades\Cache;
use InetStudio\AdminPanel\Events\Auth\ChangeMetaEvent;

class ClearCacheListener
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
     * @param ChangeMetaEvent $event
     * @return void
     */
    public function handle(ChangeMetaEvent $event)
    {
        $object = $event->object;

        Cache::tags(['seo'])->forget('SEOService_getTags_'.md5(get_class($object).$object->id));
    }
}
