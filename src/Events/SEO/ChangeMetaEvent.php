<?php

namespace InetStudio\AdminPanel\Events\Auth;

use Illuminate\Queue\SerializesModels;

class ChangeMetaEvent
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
