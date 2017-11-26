<?php

namespace InetStudio\AdminPanel\Events\SEO;

use Illuminate\Queue\SerializesModels;

class UpdateMetaEvent
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ClearCacheEvent constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
