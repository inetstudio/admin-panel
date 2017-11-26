<?php

namespace InetStudio\AdminPanel\Listeners\Images;

use Illuminate\Support\Facades\Cache;
use InetStudio\AdminPanel\Events\Images\UpdateImageEvent;

class ClearImageCacheListener
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
     * @param UpdateImageEvent $event
     * @return void
     */
    public function handle(UpdateImageEvent $event)
    {
        $object = $event->object;
        $name = $event->name;

        Cache::forget($name.'_'.md5(get_class($object).$object->id));
    }
}
